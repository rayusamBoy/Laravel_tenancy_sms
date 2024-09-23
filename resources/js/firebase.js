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
const token_element = document.querySelector('#token-info');
const unread_nots_list = document.getElementById("unread-notifications-list");

// Handle incoming messages.
onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // Update the UI to include the received message.
    updateUI(payload);
});

function resetUI(mesg = 'loading...') {
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
            // Show permission UI.
            updateUIForPushPermissionRequired();
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        showTokenInfo('Error retrieving registration token.');
    });
}

function showTokenInfo(info) {
    // Show token in console and/or UI.
    if (token_element)
        token_element.textContent = info;
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
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            console.log('Notification permission granted.');
            resetUI();
        } else {
            console.info('Unable to get permission to notify.');
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
            console.warn('Unable to delete token. ', err);
            showTokenInfo('Unable to delete token.');
        });
    }).catch((err) => {
        console.error('Error retrieving registration token. ', err);
        showTokenInfo('Error retrieving registration token.');
    });
}

function currentViewIsMessages() {
    const try_find_element = document.getElementById("messages-row");
    return try_find_element != null;
}

function updateNotificationsCount(unread_count, math_operation) {
    let unread_nots_count_content = unread_count[0].innerText || unread_count[0].textContent;
    let badge = unread_count[0].closest('.badge');
    // Update new messages count when anywhere; except when a user is currently in messages views ie., is chatting
    if (!currentViewIsMessages()) {
        if (badge.classList.contains('badge-secondary')) {
            badge.classList.add('badge-info');
            badge.classList.remove('badge-secondary');
        }
        switch (math_operation) {
            case "add":
                for (let n = 0; n < unread_count.length; n++)
                    unread_count[n].innerHTML = parseInt(unread_nots_count_content) + 1;
            case "sub":
                for (let n = 0; n < unread_count.length; n++)
                    unread_count[n].innerHTML = parseInt(unread_nots_count_content) - 1;
        }
    }
}

// Add a notification to the notifications UI.
function updateUI(payload) {
    const unread_count = document.getElementsByClassName("unread-" + payload.data.type + "-count");

    if (typeof unread_count[0] !== 'undefined' || unread_count.length > 0) {
        updateNotificationsCount(unread_count, "add");

        if (payload.data.type === "notifications") {
            const li = document.createElement('li');
            // Create the strong element for the title
            const strong = document.createElement('strong');
            strong.textContent = payload.data.title;
            // Create the anchor element for the URL
            const a = document.createElement('a');
            a.href = payload.data.url;
            a.target = "_blank";
            a.textContent = payload.data.url_title;
            // Create the small element for the created_at date
            const small = document.createElement('small');
            small.className = 'float-right';
            small.textContent = payload.data.created_at;
            // Append the elements to the list item
            li.appendChild(strong);
            li.appendChild(document.createTextNode(' - '));
            li.appendChild(a);
            li.appendChild(document.createTextNode(' '));
            li.appendChild(small);

            unread_nots_list.appendChild(li);
        }
    }

    if (payload.data.type === "notification_marked_as_read") {
        // If the notification was read. Remove from the list of unread ones.
        document.getElementById("unread-" + payload.data.id).remove();
        const unread_nots_count = document.getElementsByClassName("unread-notifications-count");
        updateNotificationsCount(unread_nots_count, "sub");
    }

    if (payload.data.type === "tenancy") {
        flash({msg: payload.data.body, type: 'info'});
    }
}

function updateUIForPushEnabled() {
    const info = "You successfully granted permission for web push notifications, and registration was successful. " +
        "To disable push notifications for this site, please adjust the settings manually in your browser.";
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