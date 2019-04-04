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
    "revision": "314d41e7ddb1b01c6a0433c2242234d4"
  },
  {
    "url": "2.1/installation.html",
    "revision": "482e325dae5c8361cf4571397d28cbdf"
  },
  {
    "url": "2.1/using-the-field.html",
    "revision": "b1bff30dbc7d0aefdef40e39bf4b181e"
  },
  {
    "url": "2.1/using-the-tool.html",
    "revision": "8bbd09d8b30fdf94ffd9a96acf17e018"
  },
  {
    "url": "404.html",
    "revision": "69b13ae2cb8d55fb0cd109b29be76f54"
  },
  {
    "url": "assets/css/0.styles.acbcc131.css",
    "revision": "adc7777aa0def3cdb073bac9031a38d1"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/2.84817a29.js",
    "revision": "88a50d356832a4aee33f105c016fe614"
  },
  {
    "url": "assets/js/3.114bb142.js",
    "revision": "2fba639b9d96440213c92e9a4d94de4e"
  },
  {
    "url": "assets/js/4.f50f0135.js",
    "revision": "7c3d3ccae0207cc142ecedade13df73a"
  },
  {
    "url": "assets/js/5.fb4e4560.js",
    "revision": "400a11a9f0a0102e0b757f442aa4e6e6"
  },
  {
    "url": "assets/js/6.8f01bc78.js",
    "revision": "6531f96898008a9523eb9da82dda77fd"
  },
  {
    "url": "assets/js/7.e917d74a.js",
    "revision": "cb841bbb7ba5519f7261ad09189eb99c"
  },
  {
    "url": "assets/js/8.7eb586c9.js",
    "revision": "96e584c7b994a13aa55791c93c33f368"
  },
  {
    "url": "assets/js/app.adbbda11.js",
    "revision": "c760e02a13f07b12063e2dcaa4885195"
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
    "revision": "cec9911cc9a665bc01f743da66c7e224"
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
