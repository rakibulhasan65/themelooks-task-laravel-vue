// import "./bootstrap";

import { createApp } from "vue";
import PosPage from "./components/PosPage.vue";

const app = createApp({});
app.component("pos-page", PosPage);
app.mount("#app");
