type EventType = 'click' | 'hover' | 'impression';

declare const _PAN_CSRF_TOKEN: string;

function observeDom(callback: MutationCallback) {
    const observer = new MutationObserver(callback);

    observer.observe(document.body, { childList: true, subtree: true });
}

(function () {
    let queue: Array<{ type: EventType; blueprint: string }> = [];
    let queueTimeout: number | null = null;
    let elementsAlreadyImpressed: Array<string> = [];

    const commit = function (): void {
        if (queue.length === 0) {
            return;
        }

        const onGoingQueue = queue.slice();
        queue = [];

        navigator.sendBeacon(
            '/pan/events',
            new Blob(
                [
                    JSON.stringify({
                        events: onGoingQueue,
                        _token: _PAN_CSRF_TOKEN,
                    }),
                ],
                {
                    type: 'application/json',
                }
            )
        );
    };

    const queueCommit = function (): void {
        queueTimeout && clearTimeout(queueTimeout);

        // @ts-ignore
        queueTimeout = setTimeout(commit, 1000);
    }

    const send = function (el: Event, event: EventType): void {
        const target: HTMLElement = el.target as HTMLElement;
        const pan: HTMLElement | null = target.closest('[data-pan]');

        if (pan !== null) {
            const blueprint: string|null = pan.getAttribute('data-pan');

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

    const detectImpressions = function (): void {
        const elementsBeingImpressed: NodeListOf<Element> = document.querySelectorAll('[data-pan]');

        elementsBeingImpressed.forEach((element: Element) => {
            const blueprint: string|null = element.getAttribute('data-pan');

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

            elementsAlreadyImpressed.forEach((blueprint: string) => {
                // check if element still exists in the DOM, if not, remove from elementsAlreadyImpressed
                const element = document.querySelector(`[data-pan="${blueprint}"]`);

                if (element === null) {
                    elementsAlreadyImpressed = elementsAlreadyImpressed.filter((element) => element !== blueprint);
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

        document.addEventListener('inertia:start', (event) => {
            elementsAlreadyImpressed = [];
        });

        document.addEventListener('inertia:finish', (event) => {
            //
        })

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
