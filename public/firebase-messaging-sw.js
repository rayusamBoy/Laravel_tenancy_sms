// Scripts for firebase and firebase messaging
importScripts('https://www.gstatic.com/firebasejs/10.13.0/firebase-app-compat.js')
importScripts('https://www.gstatic.com/firebasejs/10.13.0/firebase-messaging-compat.js')
importScripts("assets/js/firebase-config.js")

firebase.initializeApp(firebaseConfig);

// Retrieve firebase messaging
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  if (payload.data.type === "notification_marked_as_read")
    return;

  const notificationTitle = payload.data.title;
  const notificationOptions = {
    'icon': payload.data.icon,
    'body': payload.data.body,
    'click_action': payload.data.url,
  };

  self.registration.showNotification(notificationTitle,
    notificationOptions);
});