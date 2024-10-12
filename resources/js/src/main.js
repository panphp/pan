function observeDom(callback) {
    var observer = new MutationObserver(callback);
    observer.observe(document.body, { childList: true, subtree: true });
}
(function () {
    var queue = [];
    var queueTimeout = null;
    var elementsAlreadyImpressed = [];
    var commit = function () {
        if (queue.length === 0) {
            return;
        }
        var onGoingQueue = queue.slice();
        queue = [];
        navigator.sendBeacon('/pan/events', new Blob([
            JSON.stringify({
                events: onGoingQueue,
                _token: _PAN_CSRF_TOKEN,
            }),
        ], {
            type: 'application/json',
        }));
    };
    var queueCommit = function () {
        queueTimeout && clearTimeout(queueTimeout);
        // @ts-ignore
        queueTimeout = setTimeout(commit, 1000);
    };
    var send = function (el, event) {
        var target = el.target;
        var pan = target.closest('[data-pan]');
        if (pan !== null) {
            var blueprint = pan.getAttribute('data-pan');
            if (blueprint === null) {
                return;
            }
            queue.push({
                type: event,
                blueprint: blueprint,
            });
            queueCommit();
        }
    };
    var detectImpressions = function () {
        var elementsBeingImpressed = document.querySelectorAll('[data-pan]');
        elementsBeingImpressed.forEach(function (element) {
            var blueprint = element.getAttribute('data-pan');
            if (blueprint === null) {
                return;
            }
            if (element.getBoundingClientRect().top < window.innerHeight) {
                if (elementsAlreadyImpressed.includes(blueprint)) {
                    return;
                }
                elementsAlreadyImpressed.push(blueprint);
                queue.push({
                    type: 'impression',
                    blueprint: blueprint,
                });
            }
        });
        queueCommit();
    };
    document.addEventListener('DOMContentLoaded', function () {
        observeDom(function () {
            detectImpressions();
            elementsAlreadyImpressed.forEach(function (blueprint) {
                // check if element still exists in the DOM, if not, remove from elementsAlreadyImpressed
                var element = document.querySelector("[data-pan=\"".concat(blueprint, "\"]"));
                if (element === null) {
                    elementsAlreadyImpressed = elementsAlreadyImpressed.filter(function (element) { return element !== blueprint; });
                }
            });
        });
        detectImpressions();
        document.addEventListener('click', function (event) {
            send(event, 'click');
        });
        document.addEventListener('mouseover', function (event) {
            send(event, 'hover');
        });
        document.addEventListener('scroll', function (event) {
            detectImpressions();
        });
        document.addEventListener('inertia:start', function (event) {
            elementsAlreadyImpressed = [];
        });
        document.addEventListener('inertia:finish', function (event) {
            //
        });
        window.addEventListener('beforeunload', function (event) {
            if (queue.length === 0) {
                return;
            }
            event.preventDefault();
            event.returnValue = '';
            commit();
        });
    });
})();
