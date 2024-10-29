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
    'title': payload.data.title,
    'icon': payload.data.icon,
    'body': payload.data.body,
  };

  self.registration.showNotification(notificationTitle, notificationOptions);

  // Notification click event listener
  self.addEventListener("notificationclick", (e) => {
    // Close the notification popout
    e.notification.close();
    // Get all the Window clients
    e.waitUntil(
      clients.matchAll({ includeUncontrolled: true, type: "window" }).then((clientsArr) => {
        // If a Window tab matching the targeted URL already exists, focus that;
        const hadWindowToFocus = clientsArr.some((windowClient) =>
          windowClient.url === payload.data.url
            ? (windowClient.focus(), true)
            : false,
        );
        // Otherwise, open a new tab to the applicable URL and focus it.
        if (!hadWindowToFocus)
          clients
            .openWindow(payload.data.url)
            .then((windowClient) => (windowClient ? windowClient.focus() : null));
      }),
    );
  });
});