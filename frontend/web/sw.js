// Install service worker

/*self.addEventListener('install', e => {
    console.log(`Service worker installed..`);
});*/

var CACHE_NAME = 'ess-v0.01b';
var urlsToCache = [
  '/',
];


self.addEventListener('install', function (event) {
  // Perform install steps
  self.skipWaiting()
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function (cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache, {
          redirect: 'follow'
        });
      })
  );
});

// Listen to activate event

self.addEventListener('activate', e => {
  console.log(`Service worker activated.`)
});

// Listen to fetch event

self.addEventListener('fetch', e => {
  console.log('Fetch event occurred', e);
});