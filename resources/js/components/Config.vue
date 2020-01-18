<template>
  <div class="container">
    <not-found v-if="!$gate.isAdminOrAuthor()"></not-found>
    <div class="row">
      <div class="col-4 col-sm-2 tabs-config mt-4 mb-4">
        <div
          class="nav flex-column nav-tabs h-100"
          id="vert-tabs-tab"
          role="tablist"
          aria-orientation="vertical"
        >
          <a
            class="nav-link active"
            id="vert-tabs-users-tab"
            data-toggle="pill"
            href="#vert-tabs-users"
            role="tab"
            aria-controls="vert-tabs-users"
            aria-selected="true"
          >Usuários</a>
          <a
            class="nav-link"
            id="vert-tabs-profile-tab"
            data-toggle="pill"
            href="#vert-tabs-profile"
            role="tab"
            aria-controls="vert-tabs-profile"
            aria-selected="false"
          >Perfil</a>
          <a
            class="nav-link"
            id="vert-tabs-categories-tab"
            data-toggle="pill"
            href="#vert-tabs-categories"
            role="tab"
            aria-controls="vert-tabs-categories"
            aria-selected="false"
          >Categorias</a>
          <a
            class="nav-link"
            id="vert-tabs-accounts-tab"
            data-toggle="pill"
            href="#vert-tabs-accounts"
            role="tab"
            aria-controls="vert-tabs-accounts"
            aria-selected="false"
          >Contas</a>
          <a
            class="nav-link"
            id="vert-tabs-settings-tab"
            data-toggle="pill"
            href="#vert-tabs-settings"
            role="tab"
            aria-controls="vert-tabs-settings"
            aria-selected="false"
          >Configurações</a>
        </div>
      </div>
      <div class="col-8 col-sm-10 container-content-config mt-4 mb-4">
        <div class="tab-content" id="vert-tabs-tabContent">
          <div
            class="tab-pane text-left fade show active"
            id="vert-tabs-users"
            role="tabpanel"
            aria-labelledby="vert-tabs-users-tab"
          >
            <users></users>
          </div>
          <div
            class="tab-pane fade"
            id="vert-tabs-profile"
            role="tabpanel"
            aria-labelledby="vert-tabs-profile-tab"
          >
            <profile></profile>
          </div>
          <div
            class="tab-pane fade"
            id="vert-tabs-accounts"
            role="tabpanel"
            aria-labelledby="vert-tabs-accounts-tab"
          >
            <accounts></accounts>
          </div>
          <div
            class="tab-pane fade"
            id="vert-tabs-categories"
            role="tabpanel"
            aria-labelledby="vert-tabs-categories-tab"
          >
            <categories></categories>
          </div>
          <div
            class="tab-pane fade"
            id="vert-tabs-settings"
            role="tabpanel"
            aria-labelledby="vert-tabs-settings-tab"
          >Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Profile from "./Profile";
import Users from "./Users";
import Accounts from "./Accounts";
import Categories from "./Categories";

export default {
  components: { Swatches, Profile, Users, Accounts, Categories },

  mounted() {
    this.$Progress.start();

    this.$Progress.finish();
    console.log("Component mounted.");
  }
};
</script>