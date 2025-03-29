import "@mdi/font/css/materialdesignicons.css";
import { createApp } from "vue";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "vuetify/styles";
import "./bootstrap";

import ProductInventory from "./components/ProductInventory.vue";

import axios from "axios";
window.axios = axios;

// Add CSRF token to all axios requests
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;

// Get CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found");
}

const vuetify = createVuetify({
    components,
    directives,
});

const app = createApp({});

app.component("ProductInventory", ProductInventory);
app.use(vuetify);
console.log("Vue is mounting...");
app.mount("#app");
