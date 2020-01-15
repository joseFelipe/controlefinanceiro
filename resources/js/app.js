require("./bootstrap");

window.Vue = require("vue");
import Moment from "moment";
import { Form, HasError, AlertError } from "vform";
import VueProgressBar from "vue-progressbar";
import Swal from "sweetalert2";
import Swatches from "vue-swatches";

// Import the styles too, globally
import "vue-swatches/dist/vue-swatches.min.css";

import Gate from "./Gate";

Vue.prototype.$gate = new Gate(window.user);

window.swal = Swal;
window.Form = Form;
window.Swatches = Swatches;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component("pagination", require("laravel-vue-pagination"));

import VueRouter from "vue-router";

Vue.use(VueRouter);

Vue.use(VueProgressBar, {
  color: "rgb(143, 255, 199)",
  failedColor: "red",
  height: "2px"
});

const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: toast => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  }
});

window.Toast = Toast;

window.Fire = new Vue();

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
  "example-component",
  require("./components/ExampleComponent.vue").default
);

const routes = [
  {
    path: "/dashboard",
    component: require("./components/Dashboard.vue").default
  },
  {
    path: "/profile",
    component: require("./components/Profile.vue").default
  },
  {
    path: "/developer",
    component: require("./components/Developer.vue").default
  },
  { path: "/users", component: require("./components/Users.vue").default },
  {
    path: "/accounts",
    component: require("./components/Accounts.vue").default
  },
  {
    path: "/categories",
    component: require("./components/Categories.vue").default
  }
];

const router = new VueRouter({
  mode: "history",
  routes
});

Vue.filter("accountType", function(text) {
  switch (text) {
    case "current":
      return "Corrente";
    case "money":
      return "Dinheiro";
    case "salary":
      return "Salário";
    case "saving":
      return "Poupança";
    default:
      return "Erro";
  }
});

Vue.filter("upText", function(text) {
  return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter("Color", function(color) {
  return (
    "background-color: " +
    color +
    "; width: 15px; height: 15px; border-radius: 50%; margin-right: 0.5%;"
  );
});

Vue.filter("date_formatted", function(date) {
  return Moment(date).format("DD/MM/YY");
});

Vue.component(
  "passport-clients",
  require("./components/passport/Clients.vue").default
);

Vue.component(
  "passport-authorized-clients",
  require("./components/passport/AuthorizedClients.vue").default
);

Vue.component(
  "passport-personal-access-tokens",
  require("./components/passport/PersonalAccessTokens.vue").default
);

Vue.component("not-found", require("./components/NotFound.vue").default);

const app = new Vue({
  el: "#app",
  router,

  data: {
    search: ""
  },

  methods: {
    searchSomething: _.debounce(() => {
      Fire.$emit("search");
    }, 1000),

    printPage() {
      window.print();
    }
  }
});
