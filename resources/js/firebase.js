import { initializeApp } from "firebase/app";
import { deleteToken, getMessaging, getToken, onMessage } from 'firebase/messaging';
import "../../public/assets/js/firebase-config";

// Initialize Firebase
const firebase = initializeApp(firebaseConfig);
const messaging = getMessaging(firebase);
const vapidKey = import.meta.env.VITE_VAPID_KEY;

const token_div = 'token-div';
const permission_div = 'permission-div';
const request_permission_button = document.getElementById('request-permission-button');
const delete_token_button = document.getElementById('delete-token-button');
const token_info = document.getElementById('token-info');
const unread_nots_list = document.querySelectorAll(".unread-notifications-list");

// Handle incoming messages.
onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // Update the UI to include the received message.
    updateUI(payload);
});

function resetUI(mesg = 'Loading...') {
    showTokenInfo(mesg);
    // Get registration token. Initially this makes a network call, once retrieved
    // subsequent calls to getToken will return from cache.
    getToken(messaging, { vapidKey }).then((currentToken) => {
        if (currentToken) {
            sendTokenToServer(currentToken, "POST");
            updateUIForPushEnabled();
        } else {
            // Show permission request.
            console.log('No registration token available. Request permission to generate one.');
            showTokenInfo('No registration token available. Request permission to generate one.');
            // Show permission UI.
            updateUIForPushPermissionRequired();
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        showTokenInfo('Error while retrieving registration token.');
        showHideDiv('delete-token-button', false);
        showHideDiv(permission_div, false);
    });
}

function showTokenInfo(info) {
    // Show token in console and/or UI.
    if (token_info) {
        token_info.textContent = info;
        token_info.parentElement.parentElement.style.display = 'block';
        token_info.parentElement.classList.remove('alert-warning', 'alert-success', 'alert-info');
        token_info.parentElement.classList.add('alert-' + (info.includes('Error') ? 'warning' : (info.includes('Success') ? 'success' : 'info')));
    }
}

// Send the registration token to application server:
function sendTokenToServer(currentToken, method) {
    console.log('Sending token to server...', currentToken);
    updateTokenToServer(currentToken, method);
}

// The "firebase_url" here is defined in "resources\views\partials\js\custom.blade.php" path.
async function updateTokenToServer(current_token, method) {
    return fetch(firebase_url, {
        headers: {
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
            "Content-Type": "application/json"
        },
        credentials: "same-origin",
        method: method,
        body: JSON.stringify({
            'token': current_token
        }),
    });
}

function showHideDiv(divId, show) {
    const div = document.querySelector('#' + divId);
    if (div)
        if (show) {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';
        }
}

function requestPermission() {
    console.log('Requesting permission...');
    showTokenInfo('Requesting permission...');
    showHideDiv(token_div, false);
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            console.log('Notification permission granted.');
            showTokenInfo('Success; notification permission granted.');
            resetUI();
        } else {
            console.info('Error; unable to get permission to notify, permission ' + permission + '.');
            showTokenInfo('Error; unable to get permission to notify, permission ' + permission + '. Please enable notifications manually.');
            showHideDiv('delete-token-button', false);
        }
    });
}

function deleteTokenFromFirebase() {
    showTokenInfo('Deleting...');
    // Delete registration token.
    getToken(messaging).then((currentToken) => {
        deleteToken(messaging).then(() => {
            console.log('Token deleted.', currentToken);
            sendTokenToServer(currentToken, "DELETE");
            resetUI('Token deleted. Requesting a new one...');
        }).catch((err) => {
            console.warn('Error unable to delete token. ', err);
            showTokenInfo('Error unable to delete token.');
        });
    }).catch((err) => {
        console.error('Error retrieving registration token. ', err);
        showTokenInfo('Error retrieving registration token.');
        showHideDiv(token_div, false);
    });
}

function updateNotificationsCount(unread_count, math_operation) {
    let unread_nots_count_content = unread_count.innerText || unread_count.textContent;
    switch (math_operation) {
        case "add":
            unread_count.innerHTML = parseInt(unread_nots_count_content) + 1;
            break;
        case "sub":
            unread_count.innerHTML = parseInt(unread_nots_count_content) - 1;
            break;
    }
}

// Add a notification to the notifications UI.
function updateUI(payload) {
    const unread_count = document.querySelectorAll(".unread-" + payload.data.type + "-count");

    if (typeof unread_count !== 'undefined' || unread_count.length > 0) {
        unread_count.forEach(function (count) {
            updateNotificationsCount(count, "add");
        });

        unread_nots_list.forEach(function (list) {
            if (payload.data.type === "notifications") {
                const li = document.createElement('li');
                // Create the strong element for the title
                const strong = document.createElement('strong');
                strong.textContent = payload.data.title;
                // Create the anchor element for the URL
                const a = document.createElement('a');
                if (payload.data.url)
                    a.href = payload.data.url;
                a.target = "_blank";
                a.textContent = payload.data.url_title;
                // Create the small element for the created_at date
                const small = document.createElement('small');
                small.className = 'float-right';
                small.textContent = payload.data.created_at;
                // Append the elements to the list item
                a.appendChild(strong);
                li.appendChild(a);
                li.appendChild(small);

                list.appendChild(li);
            }
        });
    }

    if (payload.data.type === "notification_marked_as_read") {
        // If the notification was read. Remove from the list of unread ones.
        var unread_li = document.querySelectorAll(".unread-" + payload.data.id);
        unread_li.forEach(function (li) {
            li.remove();
        });

        var unread_nots_count = document.querySelectorAll(".unread-notifications-count");
        if (typeof unread_count !== 'undefined' || unread_count.length > 0) {
            unread_nots_count.forEach(function (count) {
                updateNotificationsCount(count, "sub");
            });
        }
    }

    if (payload.data.type === "tenancy") {
        pop({ title: payload.data.title, msg: payload.data.body, type: 'info', timer: 0 });
    }
}

function updateUIForPushEnabled() {
    const info = "You’ve granted permission for web push notifications. To disable them, adjust the settings manually in your device.";
    showHideDiv(token_div, true);
    showHideDiv(permission_div, false);
    showTokenInfo(info);
}

function updateUIForPushPermissionRequired() {
    showHideDiv(token_div, false);
    showHideDiv(permission_div, true);
}

if (request_permission_button)
    request_permission_button.addEventListener('click', requestPermission);
if (delete_token_button)
    delete_token_button.addEventListener('click', deleteTokenFromFirebase);

resetUI();