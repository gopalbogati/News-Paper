;
(function (ns) {

    var Exception = ns.Exception;

    // Common Http Verbs
    ns.HTTP_GET = 'GET';
    ns.HTTP_PUT = 'PUT';
    ns.HTTP_HEAD = 'HEAD';
    ns.HTTP_POST = 'POST';
    ns.HTTP_PATCH = 'PATCH';
    ns.HTTP_DELETE = 'DELETE';

    // Misc
    ns.CONTENT_TYPE_JSON = 'application/json';

    function Http() {

        /**
         * An array of default handlers for the request promise
         *
         * @type {Array}
         */
        var defaultHandlers = [];

        this.request = function (url, method, data) {
            data = filterData(data);
            var promise = createXhrPromise(url, method, data);

            // Add a new handler 'error' to the promise
            addErrorHandler(promise);

            // register default handlers in the promise
            registerDefaultHandlers(promise);

            return promise;
        };

        this.get = function (url, data) {
            return this.request(url, ns.HTTP_GET, data);
        };

        this.head = function (url, data) {
            return this.request(url, ns.HTTP_HEAD, data);
        };

        this.post = function (url, data) {
            return this.request(url, ns.HTTP_POST, data);
        };

        this.delete = function (url, data) {
            return this.request(url, ns.HTTP_DELETE, data);
        };

        this.put = function (url, data) {
            return this.request(url, ns.HTTP_PUT, data);
        };

        this.patch = function (url, data) {
            return this.request(url, ns.HTTP_PATCH, data);
        };

        this.addDefaultHandler = function (key, handler) {
            if (typeof (handler) !== 'function') {
                throw new Exception('Invalid handler provided.');
            }

            defaultHandlers.push({key: key, handler: handler});
        };

        this.getDefaultHandlers = function () {
            return defaultHandlers;
        };

        /**
         * Ensure the payload to be sent is a regular JSON data
         */
        function filterData(data) {
            // If data is an array, ensure that there aren't any null items
            if ($.isArray(data)) {
                var temp = [];
                for (var i in data) {
                    var item = data[i];
                    if (item) {
                        temp.push(item);
                    }
                }
                data = temp;
            }

            // If data is an object change it to JSON string
            if (typeof (data) === 'object') {
                data = JSON.stringify(data);
            }

            return data;
        }

        function createXhrPromise(url, method, payload) {
            var config = {
                url: url,
                type: method,
                data: payload,
                dataType: 'json'
            };

            // Make Content-Type: application/json only if it has payload
            // That is don't send content-type header explicitly if no payload is present
            if (payload) {
                config.contentType = ns.CONTENT_TYPE_JSON;
            }

            return $.ajax(config);
        }

        function registerDefaultHandlers(promise) {
            defaultHandlers.forEach(function (handler) {
                if (promise[handler.key]) {
                    promise[handler.key](handler.handler);
                }
            });
        }

        function addErrorHandler(promise) {
            promise.error = function (callback) {
                promise.fail(function (jqXHR, textStatus, errorThrown) {
                    var response = jqXHR.responseJSON || jqXHR.responseText;

                    callback(response, textStatus, jqXHR, errorThrown);
                });
                return promise;
            };
        }
    };

    ns.http = new Http();
})(news.core);
