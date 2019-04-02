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
    "revision": "92555419768ba7aa48921df6bb1fc7c0"
  },
  {
    "url": "2.1/installation.html",
    "revision": "6e8cd4cde10ed1ec57f049daa9967a33"
  },
  {
    "url": "2.1/using-the-field.html",
    "revision": "2c991ae6345d8b1003479cd6164fa993"
  },
  {
    "url": "2.1/using-the-tool.html",
    "revision": "055813f1f7f5defed3ff8ef0b8017f43"
  },
  {
    "url": "404.html",
    "revision": "3fce57e953e544642b6115c993df879d"
  },
  {
    "url": "assets/css/0.styles.9e48015b.css",
    "revision": "53de47a02e27c3193562a6df7f1e3d59"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/3.0cc77c53.js",
    "revision": "1d03b3891ddbcc0261d80351ffad84b5"
  },
  {
    "url": "assets/js/4.e7bb0e1c.js",
    "revision": "77a7a4b13d9d8376dbd07041860cdd89"
  },
  {
    "url": "assets/js/5.c3786dd5.js",
    "revision": "c9600d9e9384b9e08e23b4a817ca4114"
  },
  {
    "url": "assets/js/6.1ac5ea5d.js",
    "revision": "e8ade2e0a987331136eac225c6a6013d"
  },
  {
    "url": "assets/js/7.1d9079bc.js",
    "revision": "588b8bc8a41db890ae227a3a6302ea98"
  },
  {
    "url": "assets/js/8.01a5c86f.js",
    "revision": "9e0c58856fcec63faabaf4faecfc1175"
  },
  {
    "url": "assets/js/9.74550dcd.js",
    "revision": "209ababa94b6fc2a6ad0e15216425c88"
  },
  {
    "url": "assets/js/app.ebad8b89.js",
    "revision": "1849640b0ee833ed7186275085dadad2"
  },
  {
    "url": "assets/js/vendors~docsearch.98cbaf5c.js",
    "revision": "78498d45a2afd0e5941d6a76c9d79799"
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
    "revision": "c0a05b7c6c47625925fcb266d39f908e"
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
