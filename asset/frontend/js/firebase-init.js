/**
 * Firebase Cloud Messaging (FCM) Initialization
 * Estuaire Visa - Push Notifications
 */

// Import the functions you need from the SDKs
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging.js';

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyD8SNwwiUtDtVyMUpwVwHdQIt-nMpDfq4Y",
    authDomain: "immigraation-de-l-estuaire.firebaseapp.com",
    projectId: "immigraation-de-l-estuaire",
    storageBucket: "immigraation-de-l-estuaire.firebasestorage.app",
    messagingSenderId: "739124365651",
    appId: "1:739124365651:web:b9c9c8b1f31f0f0a6bb9a6",
    measurementId: "G-VX3GMCJDBB"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging
const messaging = getMessaging(app);

// VAPID Key for Web Push
const vapidKey = 'BHdr5B1boyIdmicumPceuCFSEh7fUg0lW4RlfmaQCEXExGFDEHlrElfsOyjQ4tRmlZ1HO1CwfjQfrIVmMn8Fk0A';

/**
 * Request permission for notifications and get FCM token
 */
window.requestNotificationPermission = async function() {
    try {
        // Check if service workers are supported
        if (!('serviceWorker' in navigator)) {
            console.error('Service workers are not supported in this browser');
            return { success: false, error: 'Service workers not supported' };
        }

        // Check if notifications are supported
        if (!('Notification' in window)) {
            console.error('Notifications are not supported in this browser');
            return { success: false, error: 'Notifications not supported' };
        }

        // Check current permission status
        if (Notification.permission === 'denied') {
            console.error('Notification permission denied');
            return { success: false, error: 'Permission denied' };
        }

        // Request notification permission
        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            console.log('Notification permission not granted');
            return { success: false, error: 'Permission not granted' };
        }

        // Register service worker
        const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
        console.log('Service Worker registered:', registration);

        // Wait for service worker to be ready
        await navigator.serviceWorker.ready;
        console.log('Service Worker is ready');

        // Get FCM token
        const token = await getToken(messaging, {
            vapidKey: vapidKey,
            serviceWorkerRegistration: registration
        });

        if (token) {
            console.log('FCM Token:', token);

            // Save token to server
            const response = await fetch('/user/save-fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token: token })
            });

            const data = await response.json();

            if (data.success) {
                console.log('FCM token saved successfully');
                return { success: true, token: token };
            } else {
                console.error('Failed to save FCM token:', data.error);
                return { success: false, error: data.error };
            }
        } else {
            console.error('No registration token available');
            return { success: false, error: 'No token available' };
        }

    } catch (error) {
        console.error('Error getting notification permission:', error);
        return { success: false, error: error.message };
    }
};

/**
 * Handle foreground messages (when app is open)
 */
onMessage(messaging, (payload) => {
    console.log('Message received in foreground:', payload);

    const notificationTitle = payload.notification.title || 'Estuaire Visa';
    const notificationOptions = {
        body: payload.notification.body || 'Nouvelle notification',
        icon: payload.notification.icon || '/asset/images/logo/logo.png',
        badge: '/asset/images/logo/icon.png',
        tag: payload.notification.tag || 'default',
        data: payload.data || {},
        requireInteraction: true
    };

    // Show notification
    if (Notification.permission === 'granted') {
        const notification = new Notification(notificationTitle, notificationOptions);

        notification.onclick = function(event) {
            event.preventDefault();
            const url = payload.data.url || payload.fcmOptions?.link || '/';
            window.open(url, '_blank');
            notification.close();
        };
    }
});

// Export for use in other scripts
window.firebaseMessaging = messaging;

console.log('Firebase initialized successfully');
