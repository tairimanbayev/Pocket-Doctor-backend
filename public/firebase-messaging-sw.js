// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
  apiKey: "AIzaSyBeN-crmboWVXar6A9sqkbXAosq94pkP2g",
  authDomain: "novamed-173510.firebaseapp.com",
  databaseURL: "https://novamed-173510.firebaseio.com",
  projectId: "novamed-173510",
  storageBucket: "novamed-173510.appspot.com",
  messagingSenderId: "360301767334"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();