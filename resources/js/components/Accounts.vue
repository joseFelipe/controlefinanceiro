<template>
  <div class="container">
    <not-found v-if="!$gate.isAdminOrAuthor()"></not-found>
    <div class="row">
      <div class="col-md-12">
        <div class="card mt-5" v-if="$gate.isAdminOrAuthor()">
          <div class="card-header">
            <h3 class="card-title">Contas</h3>

            <div class="card-tools">
              <button
                type="button"
                class="btn btn-block btn-primary btn-sm"
                @click="newAccountModal()"
              >
                Adicionar conta
                <i class="fas fa-plus-circle"></i>
              </button>
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Tipo</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="account in accounts.data" :key="account.id">
                  <td>{{ account.id }}</td>
                  <td>{{ account.name }}</td>
                  <td>{{ account.type | accountType }}</td>

                  <td>
                    <a href="#" class="btn btn-primary btn-sm" @click="newAccountModal(account)">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="#" @click="deleteAccount(account.id)" class="btn btn-danger btn-sm">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <pagination :data="accounts" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newAccountModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              <span v-show="!editMode">Adicionar conta</span>
              <span v-show="editMode">Editar conta</span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateAccount() : createAccount()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Nome da conta"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has('name')
                                    }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <input
                  v-model="form.initialBalance"
                  type="text"
                  name="initialBalance"
                  placeholder="Valor inicial (R$)"
                  class="form-control"
                  :class="{
                                        'is-invalid': form.errors.has('inicialBalance')
                                    }"
                />
                <has-error :form="form" field="inicialBalance"></has-error>
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
                  <option value>Selecione o tipo de conta</option>
                  <option value="current">Corrente</option>
                  <option value="money">Dinheiro</option>
                  <option value="salary">Salário</option>
                  <option value="saving">Poupança</option>
                </select>
                <has-error :form="form" field="type"></has-error>
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
      accounts: {},
      form: new Form({
        id: "",
        name: "",
        type: "",
        initialBalance: ""
      })
    };
  },

  methods: {
    getResults(page = 1) {
      axios.get("api/accounts?page=" + page).then(response => {
        this.accounts = response.data;
      });
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

    newAccountModal(account = null) {
      if (account === null) {
        this.editMode = false;
        this.form.reset();
      } else {
        this.editMode = true;
        this.form.fill(account);
      }
      $("#newAccountModal").modal("show");
    },

    async createAccount() {
      this.$Progress.start();
      await this.form
        .post("/api/account")
        .then(() => {
          Fire.$emit("UpdateAccountsTable");
          $("#newAccountModal").modal("hide");

          Toast.fire({
            icon: "success",
            title: "Conta criada com sucesso"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          swal.fire({
            icon: "error",
            title: "Erro ao criar conta"
          });
          this.$Progress.fail();
        });
    },

    async updateAccount(id) {
      this.$Progress.start();

      this.form
        .put("api/account/" + this.form.id)
        .then(() => {
          Fire.$emit("UpdateAccountsTable");
          $("#newAccountModal").modal("hide");

          Toast.fire({
            icon: "success",
            title: "Conta alterada com sucesso"
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    async deleteAccount(id) {
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
            .delete("/api/account/" + id)
            .then(() => {
              Toast.fire({
                icon: "success",
                title: "Conta excluída com sucesso"
              });
              Fire.$emit("UpdateAccountsTable");
              this.$Progress.finish();
            })
            .catch(e => {
              swal.fire({
                icon: "error",
                title: "Erro ao excluir conta",
                text: "Você não possui permissão para exluir contas"
              });
              this.$Progress.fail();
            });
        });
    }
  },

  mounted() {
    this.$Progress.start();

    Fire.$on("search", () => {
      this.loadAccounts(true);
    });

    this.loadAccounts();

    Fire.$on("UpdateAccountsTable", () => {
      this.loadAccounts();
    });
    this.$Progress.finish();
    console.log("Component mounted.");
  }
};
</script>
