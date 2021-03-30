<template>
  <div class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <div class="input_search">
        <i class="input_icon" data-feather="search"></i>
        <input type="search" placeholder="Search here...">
    </div>
    <!-- Right navbar links -->
    <div class="user_info"><span><i data-feather="user"></i></span>  {{ getUser.surename }} {{ getUser.name }}</div>
    <a href="#" class="btn_blue" @click.prevent="logoutProfile"><i class="sidebar_icon" data-feather="log-out"></i> Chiqish</a>
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
          <li class="nav-item">
             <!-- v-if="$can('profile', 'UserController')" -->
            <router-link to="/crm/dashboard" class="nav-link">
              <i class="sidebar_icon" data-feather="home" ></i>
              <p>
                Админ панель
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/crm/profile" class="nav-link">
              <i class="sidebar_icon" data-feather="user" ></i>
              <p>
                Profile
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/crm/users" class="nav-link">
              <i class="sidebar_icon" data-feather="users" ></i>
              <p>
                Users
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/crm/tasks" class="nav-link">
              <i class="sidebar_icon" data-feather="layers" ></i>
              <p>
                Tasks
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/crm/user-task" class="nav-link">
              <i class="sidebar_icon" data-feather="clipboard" ></i>
              <p>
                User task
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/crm/region" class="nav-link">
              <i class="sidebar_icon" data-feather="globe" ></i>
              <p>
                Region
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/crm/area" class="nav-link">
              <i class="sidebar_icon" data-feather="pie-chart" ></i>
              <p>
                Area
              </p>
            </router-link>
          </li>
          <li class="nav-item has_dropdown show">
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
  </div>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
      <strong>Copyright &copy; {{ new Date().getFullYear() }} <a href="https://mintrans.uz" target="_blank">O'ZBEKISTON RESPUBLIKASI TRANSPORT VAZIRLIGI </a>.</strong>
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

    }
  },
  computed: {
    ...mapGetters(['getUser']),
  },
  async mounted(){
    await this.profileUser()
  },
  methods: {
    ...mapActions(['logout','profileUser']),
    logoutProfile(){
      this.logout();
    },

  }
};
</script>

<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
