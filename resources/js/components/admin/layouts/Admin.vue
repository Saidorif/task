<template>
  <div class="hold-transition sidebar-mini">
    <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <!-- <div class="input_search">
            <i class="input_icon" data-feather="search"></i>
            <input type="search" placeholder="Search here...">
        </div> -->
        <!-- Right navbar links -->
        <div class="user_info"><span><i data-feather="user"></i></span>  {{ getUser.surename }} {{ getUser.name }}</div>
        <a href="#" class="btn_blue" @click.prevent="logoutProfile"><i class="sidebar_icon" data-feather="log-out"></i> Чиқиш</a>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar">
        <!-- Brand Logo -->
        <div class="sidebar-header">
            <router-link class="brand-link" to="/crm">
                <span class="brand-text font-weight-light">
                    <img src="/img/logo.png" style="width: 100px;">
                </span>
            </router-link>
            <a href="#" class="btn_burger" ><i class="sidebar_icon" data-feather="menu"></i></a>
        </div>
        <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- Sidebar Menu -->
        <nav>
            <ul class="sidebar_ul" data-widget="treeview" role="menu" data-accordion="false" >
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <li class="nav-item" v-if="$can('index', 'DashboardController')">
                <!-- v-if="$can('profile', 'UserController')" -->
                <router-link to="/crm/dashboard" class="nav-link">
                <i class="sidebar_icon" data-feather="home" ></i>
                <p>
                    Aдминистратор панели
                </p>
                </router-link>
            </li>
            <li class="nav-item"  v-if="$can('profile', 'UserController')">
                <router-link to="/crm/profile" class="nav-link">
                <i class="sidebar_icon" data-feather="user" ></i>
                <p>
                    Фойдаланувчи профили
                </p>
                </router-link>
            </li>
            <li class="nav-item" v-if="$can('list', 'UserController')">
                <router-link to="/crm/users" class="nav-link" v-if="$can('profile', 'UserController')">
                <i class="sidebar_icon" data-feather="users" ></i>
                <p>
                    Фойдаланувчилар
                </p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link to="/crm/tasks" class="nav-link" v-if="$can('index', 'TaskController')">
                <i class="sidebar_icon" data-feather="layers" ></i>
                <p>
                    Топшириқлар
                </p>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link to="/crm/user-task" class="nav-link"  v-if="$can('userIndex', 'TaskController')">
                <i class="sidebar_icon" data-feather="clipboard" ></i>
                <p>
                    Топшириқларим
                </p>
                </router-link>
            </li>
            <li class="nav-item" v-if="$can('index', 'RegionController')">
                <router-link to="/crm/region" class="nav-link">
                <i class="sidebar_icon" data-feather="globe" ></i>
                <p>
                    Вилоят
                </p>
                </router-link>
            </li>
            <!-- <li class="nav-item">
                <router-link to="/crm/area" class="nav-link">
                <i class="sidebar_icon" data-feather="pie-chart" ></i>
                <p>
                    Туман
                </p>
                </router-link>
            </li> -->
            <li class="nav-item has_dropdown show" v-if="$can('index', 'RoleController')">
                <a href="#" class="nav-link">
                    <i class="sidebar_icon" data-feather="settings" ></i>
                        Setting users
                    <i class="sidebar_icon mr_0 drop_down_icon" data-feather="chevron-down" ></i>
                </a>
                <ul class="drop_down_list">
                <li class="nav-item">
                    <router-link class="nav-link" to="/crm/role">
                        Role
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/crm/conts">
                        Controller
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/crm/action">
                        Actions
                    </router-link>
                </li>
                </ul>
            </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <router-view></router-view>
                        <vue-progress-bar></vue-progress-bar>
                    </div>
                </div>
            </div>
        </div>

        <div class="jvtoast position-fixed bottom-0 end-0 p-3" style="z-index: 5">
            <div v-for="(msg, index) in notMessages"  class="toast hide" role="alert" aria-live="assertive" aria-atomic="true"  >
                <div class="toast-header">
                    <strong class="me-auto">{{ msg.username }} </strong>
                        <small>11 mins ago</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ msg.message }}
                </div>
            </div>
        </div>
    </div>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ new Date().getFullYear() }} <a href="https://mintrans.uz" target="_blank">ЎЗБЕКИСТОН РЕСПУБЛИКAСИ ТРAНСПОРТ ВAЗИРЛИГИ </a>.</strong>
        All rights reserved.
    </footer>
    </div>
</div>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  import {TokenService} from './../../../services/storage.service'
export default {
    components: {
    },
    data(){
        return {
            notMessages: []
        }
    },
    computed: {
        ...mapGetters(['getUser']),
    },
    async mounted(){
        await this.profileUser()
    },
    async created(){
        Echo.private('tender')
        .listen('TaskCreated', (e)=>{
            this.notMessages.push(e)
            this.showNat()
            this.notMessages = []
        })
    },
    methods: {
        ...mapActions(['logout','profileUser']),
        logoutProfile(){
        this.logout();
        },
        showNat(){
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl)
            })
            toastList.forEach(element => {
                element.show()
            });
        }

    }
};
</script>

<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
