/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************!*\
  !*** ./src/popupSuccess.js ***!
  \*****************************/
// import { loadState } from '@nextcloud/initial-state'

// const state = loadState('integration_ews', 'popup-data')

if (window.opener) {
  window.opener.postMessage('Success');
  window.close();
}
/******/ })()
;
//# sourceMappingURL=integration_ews-popupSuccess.js.map