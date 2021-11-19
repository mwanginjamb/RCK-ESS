// Install service worker

self.addEventListener('install', e => {
    console.log(`Service worker installed..`);
});

// Listen to activate event

self.addEventListener('activate', e => {
    console.log(`Service worker activated.`)
});

// Listen to fetch event

self.addEventListener('fetch', e => {
    console.log('Fetch event occurred', e);
});