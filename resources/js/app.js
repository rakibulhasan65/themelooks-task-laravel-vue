import "./bootstrap";

import { createApp , defineAsyncComponent } from "vue";
import PosPage from "./components/PosPage.vue";
const Pagination = defineAsyncComponent(() => import("laravel-vue-pagination"));
import "@fortawesome/fontawesome-free/css/all.css";

const app = createApp({});
app.component("pos-page", PosPage);
app.component("pagination", Pagination);
app.mount("#app");
