import './bootstrap';
import 'flowbite';
import 'flowbite-datepicker';
import 'flowbite/dist/datepicker.turbo.js';

import '@vuepic/vue-datepicker/dist/main.css'

import { createApp } from "vue/dist/vue.esm-bundler.js";

import UsersList from "./components/UsersList.vue";
// import UserDetailsModal from "./components/UserDetailsModal.vue";
import Home from "./components/Home.vue";

const app = createApp({});
app.component("UsersList", UsersList);
// app.component("UserDetail", UserDetailsModal);
app.component("Home", Home);
const mountedApp = app.mount("#app");