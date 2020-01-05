<style>
.widget-user-header {
  background-position: center center;
  background-size: cover;
}
</style>

<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12 mt-5">
        <div class="card card-widget widget-user">
          <div class="widget-user-header text-white" style="background: url(./img/user-cover.jpg)">
            <h3 class="widget-user-userna`me` text-right">Test</h3>
            <h5 class="widget-user-desc text-right">Web Designer</h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar" />
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">3,200</h5>
                  <span class="description-text">SALES</span>
                </div>
              </div>
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">13,000</h5>
                  <span class="description-text">FOLLOWERS</span>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="description-block">
                  <h5 class="description-header">35</h5>
                  <span class="description-text">PRODUCTS</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link" href="#activity" data-toggle="tab">Activity</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="settings">
                <form class="form-horizontal">
                  <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Nome</label>
                    <div class="col-sm-10">
                      <input
                        v-model="form.name"
                        name="name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid' : form.errors.has('name') }"
                        id="inputName"
                        placeholder="Nome"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input
                        v-model="form.email"
                        name="email"
                        type="email"
                        class="form-control"
                        :class="{ 'is-invalid' : form.errors.has('email') }"
                        id="inputEmail"
                        placeholder="Email"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputExperience" class="col-sm-2 col-form-label">Biografia</label>
                    <div class="col-sm-10">
                      <textarea
                        v-model="form.bio"
                        name="bio"
                        placeholder="Texto simples sobre o usuário..."
                        class="form-control"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="photo" class="col-sm-2 col-form-label">Foto</label>
                    <div class="col-sm-10">
                      <input
                        @change="updateProfileImage"
                        name="photo"
                        type="file"
                        class="form-control"
                        id="photo"
                        placeholder="Foto"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Senha</label>
                    <div class="col-sm-10">
                      <input
                        v-model="form.password"
                        type="password"
                        name="password"
                        placeholder="Senha"
                        class="form-control"
                        :class="{ 'is-invalid' : form.errors.has('password') }"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                      <button
                        @click.prevent="
                                                    updateProfileInfo
                                                "
                        type="submit"
                        class="btn btn-primary"
                      >Atualizar</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userPhoto: "",
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: ""
      })
    };
  },

  mounted() {
    console.log("Component mounted.");
  },

  methods: {
    updateProfileInfo(e) {
      this.$Progress.start();
      this.form
        .put("api/profile")
        .then(() => {
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    getProfilePhoto() {
      let photo =
        this.form.photo.length > 200
          ? this.form.photo
          : "img/profile/" + this.form.photo;
      return photo;
    },

    updateProfileImage(e) {
      let file = e.target.files[0];
      let reader = new FileReader();

      if (file["size"] < 2111775) {
        reader.onloadend = file => {
          this.form.photo = reader.result;
        };

        reader.readAsDataURL(file);
      } else {
        swal.fire({
          icon: "error",
          title: "Erro ao carregar imagem...",
          text: "Imagem selecionada maior que 2MB",
          footer: "Selecione uma imagem menor que 2MB"
        });
        return false;
      }
    }
  },

  async created() {
    await axios.get("api/profile").then(({ data }) => {
      this.form.fill(data);
    });
  }
};
</script>
