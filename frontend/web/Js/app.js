if( 'serviceWorker' in navigator) {
 
    navigator.serviceWorker.register('/sw.js')
    .then((reg) => console.log('Service worker registered', reg))
    .catch((err) => console.info('sw not registered.', err));
   
}