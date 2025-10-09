// Import Firebase scripts for service worker
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyD8SNwwiUtDtVyMUpwVwHdQIt-nMpDfq4Y",
  authDomain: "immigraation-de-l-estuaire.firebaseapp.com",
  projectId: "immigraation-de-l-estuaire",
  storageBucket: "immigraation-de-l-estuaire.firebasestorage.app",
  messagingSenderId: "739124365651",
  appId: "1:739124365651:web:b9c9c8b1f31f0f0a6bb9a6",
  measurementId: "G-VX3GMCJDBB"
};

// Initialize Firebase in service worker
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Messaging
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  const notificationTitle = payload.notification.title || 'Estuaire Visa';
  const notificationOptions = {
    body: payload.notification.body || 'Nouvelle notification',
    icon: payload.notification.icon || '/asset/images/logo/logo.png',
    badge: '/asset/images/logo/icon.png',
    tag: payload.notification.tag || 'default',
    data: payload.data || {},
    requireInteraction: true,
    actions: [
      {
        action: 'open',
        title: 'Voir',
        icon: '/asset/images/logo/icon.png'
      },
      {
        action: 'close',
        title: 'Fermer',
        icon: '/asset/images/logo/icon.png'
      }
    ]
  };

  return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification click
self.addEventListener('notificationclick', function(event) {
  console.log('[Service Worker] Notification click received.');

  event.notification.close();

  if (event.action === 'open' || !event.action) {
    // Open the notification URL or default to homepage
    const urlToOpen = event.notification.data.url || '/';

    event.waitUntil(
      clients.matchAll({
        type: 'window',
        includeUncontrolled: true
      }).then(function(clientList) {
        // Check if there is already a window/tab open with the target URL
        for (let i = 0; i < clientList.length; i++) {
          const client = clientList[i];
          if (client.url === urlToOpen && 'focus' in client) {
            return client.focus();
          }
        }
        // If not, open a new window/tab with the target URL
        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
    );
  }
});
