<template>
  <div class="container">
    <not-found v-if="!$gate.isAdminOrAuthor()"></not-found>
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card" v-if="$gate.isAdminOrAuthor()">
          <div class="card-header">
            <h3 class="card-title">Categorias</h3>

            <div class="card-tools">
              <button
                type="button"
                class="btn btn-block btn-primary btn-sm"
                @click="newCategoryModal()"
              >
                Adicionar categoria
                <i class="fas fa-plus-circle"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <ul
              id="ul-list-categories"
              v-for="category in categories.categories"
              :key="category.id"
            >
              <li>
                <div @mouseover="active = true" @mouseleave="active = false">
                  <div v-bind:style="category.color | Color"></div>
                  <p>{{ category.name }}</p>
                  <a href="#" @click="newCategoryModal(category)">
                    Editar
                    <i class="fa fa-edit"></i>
                  </a>
                  <a v-show="active" href="#" @click="deleteCategory(category.id)">
                    Excluir
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </li>

              <ul v-for="subcategory in categories.subcategories" :key="subcategory.id">
                <li v-if="category.id == subcategory.parent_id">
                  <p>{{ subcategory.name }}</p>
                  <a href="#" @click="newCategoryModal(subcategory)">
                    Editar subcategoria
                    <i class="fa fa-edit"></i>
                  </a>
                  <a href="#" @click="deleteCategory(subcategory.id)">
                    Excluir subcategoria
                    <i class="fa fa-trash"></i>
                  </a>
                </li>
              </ul>
              <hr />
            </ul>
            <!-- data.categories.forEach(category => {
            console.log(category.id, category.name);
            data.subcategories.forEach(subcategory => {
              // console.log(subcategory.id);
              // console.log(category.parent_id);
              if (category.id == subcategory.parent_id) {
                console.log("- - " + subcategory.id, subcategory.name);
              }
            });
            });-->
            <!-- <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Cor</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="category in categories.data" :key="category.id">
                  <td>{{ category.id }}</td>
                  <td>{{ category.name }}</td>
                  <td>
                    <div v-bind:style="category.color | Color"></div>
                  </td>
                  <td>
                    <a href="#" class="btn btn-primary btn-sm" @click="newCategoryModal(category)">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" @click="deleteCategory(category.id)" class="btn btn-danger btn-sm">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>-->
          </div>
          <div class="card-footer">
            <pagination :data="categories" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <span v-show="!editMode">Adicionar categoria</span>
              <span v-show="editMode">Editar categoria</span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateCategory() : createCategory()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  type="radio"
                  name="mainCategory"
                  id="mainCategory"
                  v-bind:value="true"
                  v-model="mainCategory"
                />
                <label for="mainCategory">Categoria principal</label>
                <br />
                <input
                  type="radio"
                  name="isSubCategory"
                  id="subCategory"
                  v-bind:value="false"
                  v-model="mainCategory"
                />
                <label for="subCategory">Sub-categoria</label>
                <br />
              </div>
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Nome"
                  class="form-control"
                  :class="{
                    'is-invalid': form.errors.has('name')
                  }"
                />
                <has-error :form="form" field="category"></has-error>
              </div>
              <div v-if="mainCategory" class="form__field">
                <div class="form__label">
                  <strong>Selecione uma cor:</strong>
                </div>
                <div class="form__input">
                  <swatches
                    v-model="form.color"
                    name="color"
                    :class="{
                      'is-invalid': form.errors.has('color')
                    }"
                    inline
                  ></swatches>
                  <has-error :form="form" field="color"></has-error>
                </div>
              </div>
              <div v-if="!mainCategory" class="form-group">
                <select
                  v-model="form.parentCategory"
                  name="parentCategory"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has('parentCategory')
                                    }"
                >
                  <option value>Selecione a categoria pai</option>

                  <option
                    v-for="category in categories.categories"
                    :value="category.id"
                    :key="category.id"
                  >{{ category.name}}</option>
                </select>
                <has-error :form="form" field="parentCategory"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-success">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: { Swatches }, // window.VueSwatches.default - from CDN

  data() {
    return {
      editMode: false,
      categories: {},
      mainCategory: true,
      active: false,
      form: new Form({
        id: "",
        name: "",
        color: "#1CA085",
        parentCategory: 0
      })
    };
  },

  methods: {
    // mouseOver: function() {
    //   this.active = true;
    // },
    // mouseLeave: function() {
    //   this.active = false;
    // },

    getResults(page = 1) {
      axios.get("api/categories?page=" + page).then(response => {
        this.categories = response.data;
      });
    },

    async loadCategories(search = false) {
      //Verify if the current user is admin
      if (!this.$gate.isAdminOrAuthor()) {
        return false;
      }
      this.$Progress.start();

      if (search) {
        await axios
          .get("api/findCategory?q=" + this.$parent.search)
          .then(({ data }) => (this.categories = data));
      } else {
        await axios.get("api/category").then(({ data }) => {
          this.categories = data;

          data.categories.forEach(category => {
            console.log(category.id, category.name);
            data.subcategories.forEach(subcategory => {
              // console.log(subcategory.id);
              // console.log(category.parent_id);
              if (category.id == subcategory.parent_id) {
                console.log("- - " + subcategory.id, subcategory.name);
              }
            });
          });
        });
      }

      this.$Progress.finish();
    },

    newCategoryModal(category = null) {
      if (category === null) {
        this.editMode = false;
        this.form.reset();
        this.form.color = "#1CA085";
      } else {
        this.editMode = true;
        this.form.fill(category);
      }
      $("#newCategoryModal").modal("show");
    },

    async createCategory() {
      this.$Progress.start();
      await this.form
        .post("/api/category")
        .then(() => {
          Fire.$emit("UpdateCategoriesTable");
          $("#newCategoryModal").modal("hide");

          Toast.fire({
            icon: "success",
            title: "Categoria criada com sucesso"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal.fire({
            icon: "error",
            title: "Erro ao criar categoria"
          });
          this.$Progress.fail();
        });
    },

    async updateCategory(id) {
      this.$Progress.start();

      this.form
        .put("api/category/" + this.form.id)
        .then(() => {
          Fire.$emit("UpdateCategoriesTable");
          $("#newCategoryModal").modal("hide");

          Toast.fire({
            icon: "success",
            title: "Categoria alterada com sucesso"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    async deleteCategory(id) {
      this.$Progress.start();
      await swal
        .fire({
          title: "Tem certeza que deseja excluir?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim",
          cancelButtonText: "Cancelar"
        })
        .then(result => {
          if (result.dismiss) {
            return false;
          }

          this.form
            .delete("/api/category/" + id)
            .then(() => {
              Toast.fire({
                icon: "success",
                title: "Categoria excluída com sucesso"
              });
              Fire.$emit("UpdateCategoriesTable");
              this.$Progress.finish();
            })
            .catch(e => {
              swal.fire({
                icon: "error",
                title: "Erro ao excluir categoria",
                text: "Você não possui permissão para exluir categorias"
              });
              this.$Progress.fail();
            });
        });
    }
  },

  mounted() {
    this.$Progress.start();

    Fire.$on("search", () => {
      this.loadCategories(true);
    });

    this.loadCategories();

    Fire.$on("UpdateCategoriesTable", () => {
      this.loadCategories();
    });

    this.$Progress.finish();
    console.log("Component mounted.");
  }
};
</script>
