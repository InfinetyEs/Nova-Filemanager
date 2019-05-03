/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/3.6.3/workbox-sw.js");

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "2.1/customization.html",
    "revision": "229e7e4edd0d079ea81b3e27ab2f29a8"
  },
  {
    "url": "2.1/installation.html",
    "revision": "49e1e393eed975979297136af3661caa"
  },
  {
    "url": "2.1/using-the-field.html",
    "revision": "7aeabe3b911be0007b2b29a0f7a9f46a"
  },
  {
    "url": "2.1/using-the-tool.html",
    "revision": "1a893843345903c2bc05c4a3792fba13"
  },
  {
    "url": "2.2/changelog.html",
    "revision": "82fb8e4cad72e392a29eb75a3d1ba41e"
  },
  {
    "url": "2.2/customization.html",
    "revision": "c7e6b6f7843a967fae854b82163590b1"
  },
  {
    "url": "2.2/installation.html",
    "revision": "3dd4ca2fb43aa5771b3ab14b6e6de767"
  },
  {
    "url": "2.2/using-the-field.html",
    "revision": "ce029dc803c28ce5844715610af2f08e"
  },
  {
    "url": "2.2/using-the-tool.html",
    "revision": "0d8891c8322aa03dd872ab9fd5128330"
  },
  {
    "url": "404.html",
    "revision": "aecc4a7d8c7e5b6a4b5853cf6c301171"
  },
  {
    "url": "assets/css/0.styles.90ce6159.css",
    "revision": "0ae5e5d2a8dc21c5c822d07d0923d35b"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/10.008d2386.js",
    "revision": "3ff4bcd8348cbf8621017836dd24ff43"
  },
  {
    "url": "assets/js/11.82c972ed.js",
    "revision": "a41af48e06fee7608b0b19c3d35d805e"
  },
  {
    "url": "assets/js/12.5440815d.js",
    "revision": "3e5a7527bf8cd03f6a53814a32822cf6"
  },
  {
    "url": "assets/js/13.a525c431.js",
    "revision": "d5d7797d2432b32f87b3d09c38e018f5"
  },
  {
    "url": "assets/js/2.e087bea8.js",
    "revision": "4b1456041e5428d2dcfeb0b285b2d68f"
  },
  {
    "url": "assets/js/3.41968195.js",
    "revision": "13e399c2e515bcb67eb30b9196f735ef"
  },
  {
    "url": "assets/js/4.fa9e773c.js",
    "revision": "acaf50c461d72280402f5755501a78e3"
  },
  {
    "url": "assets/js/5.5bdf36d2.js",
    "revision": "28c6ed1ec3fe66021221ef780ae26503"
  },
  {
    "url": "assets/js/6.afa15c33.js",
    "revision": "6d98ec08090aa358a68e93e2f31fa760"
  },
  {
    "url": "assets/js/7.803f1807.js",
    "revision": "e4895b82f20f4cb21d86174cb80b27f0"
  },
  {
    "url": "assets/js/8.0371b3ba.js",
    "revision": "8184d7e6bd2053f7bb363e419f19129f"
  },
  {
    "url": "assets/js/9.895a5c52.js",
    "revision": "2a65919c7589212ad84520949e1d5536"
  },
  {
    "url": "assets/js/app.d9caed40.js",
    "revision": "b062c0f587748cf81f5036d329a884d7"
  },
  {
    "url": "home.png",
    "revision": "c2e5c4faa75ba0e9df55b59057005ff5"
  },
  {
    "url": "images/icons/icon-128x128.png",
    "revision": "4222fdd0025c599a51efa50bdefccf09"
  },
  {
    "url": "images/icons/icon-144x144.png",
    "revision": "1354d3d2f323b829dada252909c0a092"
  },
  {
    "url": "images/icons/icon-152x152.png",
    "revision": "285560f96859dbdb7d955583d372b353"
  },
  {
    "url": "images/icons/icon-192x192.png",
    "revision": "6be3475bbea1c851bc642a4f8d654e23"
  },
  {
    "url": "images/icons/icon-384x384.png",
    "revision": "d48f1ac60732e5f71428ddfb61044f72"
  },
  {
    "url": "images/icons/icon-512x512.png",
    "revision": "b8665547e1d106efdf22dac4414cccbe"
  },
  {
    "url": "images/icons/icon-72x72.png",
    "revision": "72dd51fb57ffbb41a584ef4e12a5d159"
  },
  {
    "url": "images/icons/icon-96x96.png",
    "revision": "b93b8f370cbcc51b36075d1762606a0d"
  },
  {
    "url": "index.html",
    "revision": "3018e07b19cd4f28ae8d22a1697155dd"
  },
  {
    "url": "preview.png",
    "revision": "ba0bd4f12bb3163dd1a5a7a46710fcbe"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.suppressWarnings();
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
