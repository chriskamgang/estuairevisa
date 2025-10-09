<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Firebase Cloud Messaging (FCM) push notifications
    |
    */

    'api_key' => env('FIREBASE_API_KEY', 'AIzaSyD8SNwwiUtDtVyMUpwVwHdQIt-nMpDfq4Y'),

    'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'immigraation-de-l-estuaire.firebaseapp.com'),

    'project_id' => env('FIREBASE_PROJECT_ID', 'immigraation-de-l-estuaire'),

    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'immigraation-de-l-estuaire.firebasestorage.app'),

    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '739124365651'),

    'app_id' => env('FIREBASE_APP_ID', '1:739124365651:web:b9c9c8b1f31f0f0a6bb9a6'),

    'measurement_id' => env('FIREBASE_MEASUREMENT_ID', 'G-VX3GMCJDBB'),

    'vapid_key' => env('FIREBASE_VAPID_KEY', 'BHdr5B1boyIdmicumPceuCFSEh7fUg0lW4RlfmaQCEXExGFDEHlrElfsOyjQ4tRmlZ1HO1CwfjQfrIVmMn8Fk0A'),

    // Server Key for backend push notifications (legacy)
    'server_key' => env('FIREBASE_SERVER_KEY', ''),

    // Service Account credentials file path for FCM V1 API
    'credentials' => storage_path('app/firebase/firebase-credentials.json'),
];
