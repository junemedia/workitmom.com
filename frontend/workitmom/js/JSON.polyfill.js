/*
 * a dirty dirty hack; loading mootoolsCore.js seems to overwrite
 * JSON.parse and .stringify, so we need to polyfill it here if
 * necessary
 *
 * https://stackoverflow.com/questions/8580431/recovering-built-in-methods-that-have-been-overwritten
 *
 */

var iframe = document.createElement("iframe");
document.documentElement.appendChild(iframe);
var _window = iframe.contentWindow;
JSON.parse = _window.JSON.parse;
JSON.stringify = _window.JSON.stringify;
document.documentElement.removeChild(iframe);
