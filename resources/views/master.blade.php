<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Controle Financeiro</title>

    <link rel="stylesheet" href="/css/app.css" />
    <link rel="stylesheet" href="/css/main.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <div class="form-line ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" @keyup="searchSomething" v-model="search" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit" @click="searchSomething">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <aside class="main-sidebar sidebar-light-primary elevation-4">

            <a href="index3.html" class="brand-link">
                <img src="./img/effort.png" alt="Controle Financeiro Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Controle Financeiro</span>
            </a>


            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="./img/man.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            {{ Auth::user()->name }}
                            <p><i>{{ Auth::user()->type }}</i></p>
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <router-link to="/dashboard" class="nav-link" active-class="active" exact>
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/transactions" class="nav-link" active-class="active" exact>
                                <i class="nav-icon fas fa-list"></i>
                                <p>Lançamentos</p>
                            </router-link>
                        </li>
                        @can("isAdminOrAuthor")
                        <li class="nav-item">
                            <router-link to="/config" class="nav-link" active-class="active" exact>
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Configurações</p>
                            </router-link>
                        </li>
                        @endcan
                        
                        @can("isAdmin")
                        <li class="nav-item">
                            <router-link to="/developer" class="nav-link" active-class="active" exact>
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Desenvolvimento</p>
                            </router-link>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="nav-icon fas fa-power-off"></i>
                                {{ __('Sair') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper">

            <div class="content">
                <div class="container-fluid">
                    <router-view></router-view>
                    <vue-progress-bar></vue-progress-bar>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>

    @auth
    <script>
        window.user = (@json(auth() -> user()));
    </script>
    @endauth

    <script src="/js/app.js"></script>
</body>

</html>
