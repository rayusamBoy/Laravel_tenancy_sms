/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Here you may activate the broadcasting service you want.
 * Comment any service you do not want to use. You can only use one service at a time.
 * Don't forget to change Broadcast Connection in the .env file if you change the default one.
 */

//import "./pusher";
import "./reverb";