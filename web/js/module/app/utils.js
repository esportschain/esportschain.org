/**
 * H5 工具方法
 *
 * @type {{callNativeFun}}
 */
var Utils = function() {
    return {
        /**
         * webView调用APP原生方法
         *
         * @param method 调用的方法名
         * @param options 参数的键值对，参数名和顺序须与APP侧对应 {key: value, key: value}
         */
        callNativeFun: function(method, options) {
            if(typeof JsUtils != 'undefined' && typeof JsUtils[method] != 'undefined') {
                // UIWebView
                var params = [];
                for(k in options) {
                    params.push(options[k]);
                }
                try {
                    JsUtils[method].apply(JsUtils, params);
                } catch(e) {
                    console.log(e.name + ' ' + e.message);
                }
            } else if (typeof window.webkit != 'undefined') {
                // WKWebView
                options['method'] = method;
                window.webkit.messageHandlers.Utils.postMessage(options);
            } else {
                console.log('called function: ' + method + ', params: ' + $.param(options));
            }
        },
        forCallNativeFun: function(method, options) {
            var triedTimes = 0;
            var handle = setInterval(function() {
                ++triedTimes;
                if (triedTimes >= 200) {
                    clearInterval(handle);
                    return;
                }
                try {
                    if (typeof JsUtils == 'undefined' && typeof window.webkit.messageHandlers.Utils == 'undefined') {
                        return;
                    }
                } catch (err) {
                    return;
                }
                Utils.callNativeFun(method, options);
                clearInterval(handle);
            }, 10);
        }
    }
}();
