const firebaseConfig = {
    // Your web app's Firebase configuration here
    // See https://firebase.google.com/docs/web/setup
    apiKey: 'API_KEY',
    authDomain: 'PROJECT_ID.firebaseapp.com',
    projectId: 'PROJECT_ID',
    storageBucket: 'PROJECT_ID.appspot.com',
    messagingSenderId: 'SENDER_ID',
    appId: 'APP_ID',
    measurementId: 'G-MEASUREMENT_ID'
}

// Conditioned it; otherwise it will throw an error in serviceworker
if (typeof window != 'undefined')
    window.firebaseConfig = firebaseConfig; // Make it accessible by the importing script