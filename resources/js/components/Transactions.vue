<template>
  <div class="container">
    <not-found v-if="!$gate.isAdminOrAuthor()"></not-found>
    <div class="row">
      <div class="col-12">
        <div class="header-container">
          <span>Lançamentos</span>
          <button
            type="button"
            class="btn btn-secondary btn-sm"
            @click="newTransactionModal(transfer)"
          >
            nova transferência
            <i class="fas fa-exchange-alt"></i>
          </button>
          <button
            type="button"
            class="btn btn-success btn-sm"
            @click="newTransactionModal(incoming)"
          >
            nova receita
            <i class="fas fa-plus"></i>
          </button>
          <button type="button" class="btn btn-danger btn-sm" @click="newTransactionModal(expense)">
            nova despesa
            <i class="fas fa-minus"></i>
          </button>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>Descrição</th>
              <th>Valor</th>
              <th>Data</th>
              <th>Conta</th>
              <th>Categoria</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr v-for="transaction in transactions.data" :key="transaction.id"> -->
            <tr>
              <td>Almoço celeiro</td>
              <td>R$ 25,90</td>
              <td>16/01/20</td>
              <td>Viacredi</td>
              <td>Alimentação</td>

              <td class="actions-button">
                <a class="text-info" href="#" @click="newTransactionModal(transaction)">
                  editar
                  <i class="fa fa-edit"></i>
                </a>
                <a class="text-danger" href="#" @click="deleteTransaction(transaction.id)">
                  excluir
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            <tr>
              <td>Estacionamento shopping</td>
              <td>R$ 8,00</td>
              <td>09/01/20</td>
              <td>Nubank</td>
              <td>Estacionamento</td>

              <td class="actions-button">
                <a class="text-info" href="#" @click="newTransactionModal(transaction)">
                  editar
                  <i class="fa fa-edit"></i>
                </a>
                <a class="text-danger" href="#" @click="deleteTransaction(transaction.id)">
                  excluir
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <pagination :data="transactions" @pagination-change-page="getResults"></pagination>
          </tfoot>
        </table>
        <!-- </div> -->
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newTransactionModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <span v-show="!editMode">Novo lançamento</span>
              <span v-show="editMode">Editar lançamento</span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateUser() : createUser()">
            <div class="modal-body">
              <div class="form-group">
                <div class="form-group">
                  <input
                    type="radio"
                    name="expense"
                    id="expense"
                    v-bind:value="true"
                    v-model="transactionType"
                  />
                  <label for="expense">despesa</label>
                  <br />
                  <input
                    type="radio"
                    name="incoming"
                    id="incoming"
                    v-bind:value="false"
                    v-model="transactionType"
                  />
                  <label for="incoming">receita</label>
                  <br />
                  <input
                    type="radio"
                    name="transfer"
                    id="transfer"
                    v-bind:value="false"
                    v-model="transactionType"
                  />
                  <label for="incoming">transferência</label>
                  <br />
                </div>
              </div>
              <div class="form-group">
                <input
                  v-model="form.description"
                  type="text"
                  name="description"
                  placeholder="Descrição"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has('description')
                                    }"
                />
                <has-error :form="form" field="description"></has-error>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <input
                      v-model="form.value"
                      type="value"
                      name="value"
                      placeholder="Value (R$)"
                      class="form-control"
                      :class="{
                                        'is-invalid': form.errors.has('value')
                                    }"
                    />
                    <has-error :form="form" field="value"></has-error>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <input
                      v-model="form.date"
                      type="date"
                      name="date"
                      placeholder="Data"
                      class="form-control"
                      :class="{
                                        'is-invalid': form.errors.has('date')
                                    }"
                    />
                    <has-error :form="form" field="date"></has-error>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- <div class="col-sm-6">
                  <div class="form-group">
                    <select
                      required
                      v-model="form.category"
                      name="category"
                      class="form-control"
                      :class="{'is-invalid': form.errors.has('category')}"
                    >
                      <option
                        v-for="category in categories.categories"
                        :value="category.id"
                        :key="category.id"
                      >{{ category.name}}</option>
                    </select>
                    <has-error :form="form" field="category"></has-error>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <select
                      required
                      v-model="form.account"
                      name="account"
                      class="form-control"
                      :class="{'is-invalid': form.errors.has('account')}"
                    >
                      <option
                        v-for="account in accounts"
                        :value="account.id"
                        :key="account.id"
                      >{{ account.name}}</option>
                    </select>
                    <has-error :form="form" field="account"></has-error>
                  </div>
                </div>-->
              </div>
              <div class="form-group">
                <textarea
                  v-model="form.note"
                  name="note"
                  placeholder="Observação"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has('note')
                                    }"
                />
                <has-error :form="form" field="note"></has-error>
              </div>
              <div class="form-group">
                <select
                  v-model="form.type"
                  name="type"
                  placeholder="type"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has('type')
                                    }"
                >
                  <option value>Selecione um perfil de usuário</option>
                  <option value="admin">Administrador</option>
                  <option value="user">Usuário Padrão</option>
                  <option value="author">Autor</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  placeholder="Senha"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has(
                                            'password'
                                        )
                                    }"
                />
                <has-error :form="form" field="password"></has-error>
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
  data() {
    return {
      editMode: false,
      transactions: {},
      categories: {},
      accounts: {},
      transactionType: "",
      form: new Form({
        id: "",
        transactionType: "",
        description: "",
        value: "",
        date: "",
        account: "",
        category: "",
        note: ""
      })
    };
  },

  methods: {
    getResults(page = 1) {
      axios.get("api/user?page=" + page).then(response => {
        this.users = response.data;
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

          this.categories.categories.forEach(category => {
            this.$set(category, "active", false);
          });

          this.categories.subcategories.forEach(subcategory => {
            this.$set(subcategory, "active", false);
          });
        });
      }

      this.$Progress.finish();
    },

    async loadAccounts(search = false) {
      //Verify if the current user is admin
      if (!this.$gate.isAdminOrAuthor()) {
        return false;
      }
      this.$Progress.start();

      if (search) {
        await axios
          .get("api/findAccount?q=" + this.$parent.search)
          .then(({ data }) => (this.accounts = data));
      } else {
        await axios
          .get("api/account")
          .then(({ data }) => (this.accounts = data));
      }

      this.$Progress.finish();
    },

    newTransactionModal(transactionType) {
      // if (transactionType === null) {
      //   this.editMode = false;
      //   this.form.reset();
      // } else {
      //   this.editMode = true;
      //   this.form.fill(transactionType);
      // }
      $("#newTransactionModal").modal("show");
    },

    async createUser() {
      this.$Progress.start();
      await this.form
        .post("/api/user")
        .then(() => {
          Fire.$emit("RefreshTransactionsTable");
          $("#newTransactionModal").modal("hide");

          Toast.fire({
            icon: "success",
            title: "Usuário criado com sucesso"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          console.log("Erro ao criar usuário");
          this.$Progress.fail();
        });
    },

    async updateUser(id) {
      this.$Progress.start();

      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          Fire.$emit("RefreshTransactionsTable");
          $("#newTransactionModal").modal("hide");

          Toast.fire({
            icon: "success",
            title: "Usuário alterado com sucesso"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    async deleteUser(id) {
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
            .delete("/api/user/" + id)
            .then(() => {
              Toast.fire({
                icon: "success",
                title: "Usuário excluído com sucesso"
              });
              Fire.$emit("RefreshTransactionsTable");
              this.$Progress.finish();
            })
            .catch(e => {
              swal.fire({
                icon: "error",
                title: "Erro ao excluir o usuário",
                text: "Você não possui permissão para excluir usuários"
              });
              this.$Progress.fail();
            });
        });
    }
  },

  mounted() {
    this.$Progress.start();

    Fire.$on("search", () => {
      this.loadUsers(true);
    });

    this.loadCategories();
    this.loadAccounts();

    Fire.$on("RefreshTransactionsTable", () => {
      this.loadUsers();
    });
    this.$Progress.finish();
    console.log("Component mounted.");
  }
};
</script>
