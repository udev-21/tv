import './bootstrap';
import 'flowbite';

import { createApp } from "vue/dist/vue.esm-bundler.js";

import UsersList from "./components/UsersList.vue";
import UserDetailsModal from "./components/UserDetailsModal.vue";


const app = createApp({});
app.component("users-list", UsersList);
app.component("user-detail", UserDetailsModal);
const mountedApp = app.mount("#app");