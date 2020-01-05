<template>
    <div class="container">
        <not-found v-if="!$gate.isAdminOrAuthor()"></not-found>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card" v-if="$gate.isAdminOrAuthor()">
                    <div class="card-header">
                        <h3 class="card-title">Usuários</h3>

                        <div class="card-tools">
                            <button
                                type="button"
                                class="btn btn-block btn-primary btn-sm"
                                @click="newUserModal()"
                            >
                                Adicionar usuário
                                <i class="fa fa-user-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Data cadastro</th>
                                    <th>Tipo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>
                                        {{ user.created_at | date_formatted }}
                                    </td>
                                    <td>{{ user.type | upText }}</td>

                                    <td>
                                        <a
                                            href="#"
                                            class="btn btn-primary btn-sm"
                                            @click="newUserModal(user)"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteUser(user.id)"
                                            class="btn btn-danger btn-sm"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination
                            :data="users"
                            @pagination-change-page="getResults"
                        ></pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <span v-show="!editMode">Adicionar usuário</span>
                            <span v-show="editMode">Editar usuário</span>
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form
                        @submit.prevent="editMode ? updateUser() : createUser()"
                    >
                        <div class="modal-body">
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
                                <has-error
                                    :form="form"
                                    field="name"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    placeholder="Email"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('email')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="email"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <textarea
                                    v-model="form.bio"
                                    name="bio"
                                    placeholder="Texto simples sobre o usuário..."
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('bio')
                                    }"
                                />
                                <has-error :form="form" field="bio"></has-error>
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
                                    <option value
                                        >Selecione um perfil de usuário</option
                                    >
                                    <option value="admin">Administrador</option>
                                    <option value="user">Usuário Padrão</option>
                                    <option value="author">Autor</option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="type"
                                ></has-error>
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
                                <has-error
                                    :form="form"
                                    field="password"
                                ></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Fechar
                            </button>
                            <button type="submit" class="btn btn-success">
                                Salvar
                            </button>
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
            users: {},
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

    methods: {
        getResults(page = 1) {
            axios.get("api/user?page=" + page).then(response => {
                this.users = response.data;
            });
        },
        async loadUsers(search = false) {
            //Verify if the current user is admin
            if (!this.$gate.isAdminOrAuthor()) {
                return false;
            }
            this.$Progress.start();

            if (search) {
                await axios
                    .get("api/findUser?q=" + this.$parent.search)
                    .then(({ data }) => (this.users = data));
            } else {
                await axios
                    .get("api/user")
                    .then(({ data }) => (this.users = data));
            }

            this.$Progress.finish();
        },

        newUserModal(user = null) {
            if (user === null) {
                this.editMode = false;
                this.form.reset();
            } else {
                this.editMode = true;
                this.form.fill(user);
            }
            $("#newUserModal").modal("show");
        },

        async createUser() {
            this.$Progress.start();
            await this.form
                .post("/api/user")
                .then(() => {
                    Fire.$emit("UpdateUsersTable");
                    $("#newUserModal").modal("hide");

                    Toast.fire({
                        icon: "success",
                        title: "Usuário criado com sucesso"
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    swal.fire({
                        icon: "error",
                        title: "Erro ao criar o usuário"
                    });
                    this.$Progress.fail();
                });
        },

        async updateUser(id) {
            this.$Progress.start();

            this.form
                .put("api/user/" + this.form.id)
                .then(() => {
                    Fire.$emit("UpdateUsersTable");
                    $("#newUserModal").modal("hide");

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
                            Fire.$emit("UpdateUsersTable");
                            this.$Progress.finish();
                        })
                        .catch(e => {
                            swal.fire({
                                icon: "error",
                                title: "Erro ao excluir o usuário",
                                text:
                                    "Você não possui permissão para exluir usuários"
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

        this.loadUsers();

        Fire.$on("UpdateUsersTable", () => {
            this.loadUsers();
        });
        this.$Progress.finish();
        console.log("Component mounted.");
    }
};
</script>
