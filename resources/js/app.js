import "./bootstrap";

import { createApp , defineAsyncComponent } from "vue";
import PosPage from "./components/PosPage.vue";
import LaravelVuePagination from "laravel-vue-pagination";
import "@fortawesome/fontawesome-free/css/all.css";

const app = createApp({});
app.component("pos-page", PosPage);
app.component("Pagination", LaravelVuePagination);
app.mount("#app");
