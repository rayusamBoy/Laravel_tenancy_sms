import { initializeApp } from "firebase/app";
import { deleteToken, getMessaging, getToken, onMessage } from "firebase/messaging";
import "../../public/assets/js/firebase-config";

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);
const vapidKey = import.meta.env.VITE_VAPID_KEY;

// DOM Elements
const tokenDiv = "token-div";
const permissionDiv = "permission-div";
const requestPermissionButton = document.getElementById("request-permission-button");
const deleteTokenButton = document.getElementById("delete-token-button");
const tokenInfo = document.getElementById("token-info");
const unreadNotsList = document.querySelectorAll(".unread-notifications-list");

// Handle incoming messages
onMessage(messaging, (payload) => {
    console.log("Message received. ", payload);
    updateUI(payload);
});

const resetUI = (message = "Loading...") => {
    showTokenInfo(message);
    getToken(messaging, { vapidKey })
        .then((currentToken) => {
            if (currentToken) {
                sendTokenToServer(currentToken, "POST");
                updateUIForPushEnabled();
            } else {
                console.log("No registration token available. Request permission to generate one.");
                showTokenInfo("No registration token available. Request permission to generate one.");
                updateUIForPushPermissionRequired();
            }
        })
        .catch((err) => {
            console.error("Error retrieving token: ", err);
            showTokenInfo("Error while retrieving registration token.");
            showHideDiv("delete-token-button", false);
            showHideDiv(permissionDiv, false);
        });
};

const showTokenInfo = (info) => {
    if (tokenInfo) {
        tokenInfo.textContent = info;
        // Ensure the token info container is visible and update its alert class based on message content
        const container = tokenInfo.parentElement?.parentElement;
        if (container) container.style.display = "block";
        const alertBox = tokenInfo.parentElement;
        if (alertBox) {
            alertBox.classList.remove("alert-warning", "alert-success", "alert-info");
            const alertType = info.includes("Error")
                ? "warning"
                : info.includes("Success")
                    ? "success"
                    : "info";
            alertBox.classList.add(`alert-${alertType}`);
        }
    }
};

const sendTokenToServer = (currentToken, method) => {
    console.log("Sending token to server...", currentToken);
    updateTokenToServer(currentToken, method);
};

const updateTokenToServer = async (currentToken, method) => {
    return fetch(firebase_url, {
        headers: {
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json",
        },
        credentials: "same-origin",
        method,
        body: JSON.stringify({ token: currentToken }),
    });
};

const showHideDiv = (divId, show) => {
    const div = document.querySelector(`#${divId}`);
    if (div) {
        div.style.display = show ? "block" : "none";
    }
};

const requestPermission = () => {
    console.log("Requesting permission...");
    showTokenInfo("Requesting permission...");
    showHideDiv(tokenDiv, false);
    Notification.requestPermission().then((permission) => {
        if (permission === "granted") {
            console.log("Notification permission granted.");
            showTokenInfo("Success; notification permission granted.");
            resetUI();
        } else {
            console.info(`Permission ${permission} received; notifications disabled.`);
            showTokenInfo(`Error; unable to get permission to notify, permission ${permission}. Please enable notifications manually.`);
            showHideDiv("delete-token-button", false);
        }
    });
};

const deleteTokenFromFirebase = () => {
    showTokenInfo("Deleting...");
    getToken(messaging)
        .then((currentToken) => {
            deleteToken(messaging)
                .then(() => {
                    console.log("Token deleted.", currentToken);
                    sendTokenToServer(currentToken, "DELETE");
                    resetUI("Token deleted. Requesting a new one...");
                })
                .catch((err) => {
                    console.warn("Error deleting token: ", err);
                    showTokenInfo("Error unable to delete token.");
                });
        })
        .catch((err) => {
            console.error("Error retrieving registration token: ", err);
            showTokenInfo("Error retrieving registration token.");
            showHideDiv(tokenDiv, false);
        });
};

const updateNotificationsCount = (unreadCountEl, operation) => {
    const currentCount = parseInt(unreadCountEl.innerText || unreadCountEl.textContent, 10);
    unreadCountEl.innerHTML = operation === "add" ? currentCount + 1 : currentCount - 1;
};

const updateUI = (payload) => {
    // Update unread notification counts
    const unreadCountEls = document.querySelectorAll(`.unread-${payload.data.type}-count`);
    if (unreadCountEls && unreadCountEls.length > 0) {
        unreadCountEls.forEach((el) => updateNotificationsCount(el, "add"));

        // Update notifications list if type matches
        unreadNotsList.forEach((list) => {
            if (payload.data.type === "notifications") {
                const li = document.createElement("li");
                const strong = document.createElement("strong");
                strong.textContent = payload.data.title;
                const a = document.createElement("a");
                if (payload.data.url) a.href = payload.data.url;
                a.target = "_blank";
                a.textContent = payload.data.url_title;
                const small = document.createElement("small");
                small.className = "float-right";
                small.textContent = payload.data.created_at;
                a.appendChild(strong);
                li.appendChild(a);
                li.appendChild(small);
                list.appendChild(li);
            }
        });
    }

    // If a notification was marked as read, remove it from unread list and update count
    if (payload.data.type === "notification_marked_as_read") {
        document.querySelectorAll(`.unread-${payload.data.id}`).forEach((li) => li.remove());
        document.querySelectorAll(".unread-notifications-count").forEach((countEl) =>
            updateNotificationsCount(countEl, "sub")
        );
    }

    // Special handling for tenancy messages
    if (payload.data.type === "tenancy") {
        pop({ title: payload.data.title, msg: payload.data.body, type: "info", timer: 0 });
    }
};

const updateUIForPushEnabled = () => {
    const info =
        "You’ve granted permission for web push notifications. To disable them, adjust the settings manually in your device.";
    showHideDiv(tokenDiv, true);
    showHideDiv(permissionDiv, false);
    showTokenInfo(info);
};

const updateUIForPushPermissionRequired = () => {
    showHideDiv(tokenDiv, false);
    showHideDiv(permissionDiv, true);
};

if (requestPermissionButton) {
    requestPermissionButton.addEventListener("click", requestPermission);
}
if (deleteTokenButton) {
    deleteTokenButton.addEventListener("click", deleteTokenFromFirebase);
}

resetUI();
