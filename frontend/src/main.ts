import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import { createPinia } from 'pinia';

import '@mdi/font/css/materialdesignicons.css';
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "vuetify/styles";

const vuetify = createVuetify({
  components,
  directives,
});

const app = createApp(App);

const pinia = createPinia();

app.use(router);

app.use(pinia);

app.use(vuetify);

app.mount("#app");
