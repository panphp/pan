"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.window.__pan =
    exports.window.__pan ||
        {
            csrfToken: "%_PAN_CSRF_TOKEN_%",
            routePrefix: "%_PAN_ROUTE_PREFIX_%",
            observer: null,
            clickListener: null,
            mouseoverListener: null,
            inertiaStartListener: null,
            livewireNavigatedListener: null,
        };
if (exports.window.__pan.observer) {
    exports.window.__pan.observer.disconnect();
    exports.window.__pan.observer = null;
}
if (exports.window.__pan.clickListener) {
    document.removeEventListener("click", exports.window.__pan.clickListener);
    exports.window.__pan.clickListener = null;
}
if (exports.window.__pan.mouseoverListener) {
    document.removeEventListener("mouseover", exports.window.__pan.mouseoverListener);
    exports.window.__pan.mouseoverListener = null;
}
if (exports.window.__pan.inertiaStartListener) {
    document.removeEventListener("inertia:start", exports.window.__pan.inertiaStartListener);
    exports.window.__pan.inertiaStartListener = null;
}
if (exports.window.__pan.livewireNavigatedListener) {
    document.removeEventListener("livewire:navigated", exports.window.__pan.livewireNavigatedListener);
    exports.window.__pan.livewireNavigatedListener = null;
}
(function () {
    const domObserver = (callback) => {
        const observer = new MutationObserver(callback);
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
        });
        exports.window.__pan.observer = observer;
    };
    let queue = [];
    let queueTimeout = null;
    let impressed = [];
    let hovered = [];
    let clicked = [];
    let sameNameElements = [];
    const commit = () => {
        if (queue.length === 0) {
            return;
        }
        const onGoingQueue = queue.slice();
        queue = [];
        navigator.sendBeacon(`/${exports.window.__pan.routePrefix}/events`, new Blob([
            JSON.stringify({
                events: onGoingQueue,
                _token: exports.window.__pan.csrfToken,
            }),
        ], {
            type: "application/json",
        }));
    };
    const queueCommit = function () {
        queueTimeout && clearTimeout(queueTimeout);
        // @ts-ignore
        queueTimeout = setTimeout(commit, 1000);
    };
    const send = function (el, event) {
        const target = el.target;
        const element = target.closest("[data-pan]");
        if (element === null) {
            return;
        }
        const name = element.getAttribute("data-pan");
        if (name === null) {
            return;
        }
        if (event === "hover") {
            if (hovered.includes(name)) {
                return;
            }
            hovered.push(name);
        }
        if (event === "click") {
            if (clicked.includes(name)) {
                return;
            }
            clicked.push(name);
        }
        queue.push({
            type: event,
            name: name,
        });
        queueCommit();
    };
    const detectImpressions = function () {
        const elementsBeingImpressed = document.querySelectorAll("[data-pan]");
        elementsBeingImpressed.forEach((element) => {
            if (element.checkVisibility !== undefined &&
                !element.checkVisibility()) {
                return;
            }
            const name = element.getAttribute("data-pan");
            if (name === null) {
                return;
            }
            let nameElements = document.querySelectorAll(`[data-pan='${name}']`);
            if (nameElements.length > 1 && !sameNameElements.includes(name)) {
                console.warn(`PAN: Multiple (${nameElements.length}) elements with the same name '${name}' found`);
                sameNameElements.push(name);
            }
            if (impressed.includes(name)) {
                return;
            }
            impressed.push(name);
            queue.push({
                type: "impression",
                name: name,
            });
        });
        queueCommit();
    };
    domObserver(function () {
        impressed.forEach((name) => {
            const element = document.querySelector(`[data-pan='${name}']`);
            if (element === null) {
                impressed = impressed.filter((n) => n !== name);
                hovered = hovered.filter((n) => n !== name);
                clicked = clicked.filter((n) => n !== name);
            }
        });
        detectImpressions();
    });
    exports.window.__pan.clickListener = (event) => send(event, "click");
    document.addEventListener("click", exports.window.__pan.clickListener);
    exports.window.__pan.mouseoverListener = (event) => send(event, "hover");
    document.addEventListener("mouseover", exports.window.__pan.mouseoverListener);
    exports.window.__pan.inertiaStartListener = (event) => {
        impressed = [];
        hovered = [];
        clicked = [];
        detectImpressions();
    };
    document.addEventListener("inertia:start", exports.window.__pan.inertiaStartListener);
    exports.window.__pan.livewireNavigatedListener = () => {
        impressed = [];
        hovered = [];
        clicked = [];
        detectImpressions();
    };
    document.addEventListener("livewire:navigated", exports.window.__pan.livewireNavigatedListener);
    exports.window.__pan.beforeUnloadListener = function (event) {
        if (queue.length === 0) {
            return;
        }
        commit();
    };
    exports.window.addEventListener("beforeunload", exports.window.__pan.beforeUnloadListener);
})();
