import _ from 'lodash';
import axios from 'axios';

window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const getCookieValue = (name) => {
	const prefix = `${name}=`;
	const cookie = document.cookie
		.split('; ')
		.find((item) => item.startsWith(prefix));

	if (!cookie) {
		return null;
	}

	return decodeURIComponent(cookie.slice(prefix.length));
};

const runtimeApiBaseUrl = window.TIAN_API_URL || import.meta.env.VITE_API_BASE_URL;

if (runtimeApiBaseUrl) {
	window.axios.defaults.baseURL = runtimeApiBaseUrl.replace(/\/$/, '');
	window.axios.defaults.withCredentials = true;
	window.axios.defaults.withXSRFToken = true;
}

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

window.axios.interceptors.request.use((config) => {
	const xsrfToken = getCookieValue('XSRF-TOKEN');

	if (xsrfToken) {
		config.headers = config.headers || {};
		config.headers['X-XSRF-TOKEN'] = xsrfToken;
	}

	return config;
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
