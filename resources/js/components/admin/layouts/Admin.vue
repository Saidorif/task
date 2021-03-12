<template>
  <div class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="sidebar_icon fas fa-bars" style="font-size: 30px;line-height: 21px;"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="#" class="logout_btn" @click.prevent="logoutProfile">Выйти <i class="fas fa-sign-out-alt"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar">
    <!-- Brand Logo -->
    <router-link class="brand-link" to="/crm">
      <span class="brand-text font-weight-light">
        <img src="/img/logoRu.png">
      </span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- Sidebar Menu -->
      <nav>
        <ul class="nav nav-pills nav-sidebar flex-column sidebar-menu treeview" data-widget="treeview" role="menu" data-accordion="false" >
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
             <!-- v-if="$can('profile', 'UserController')" -->
            <router-link to="/crm/dashboard" class="nav-link">
              <i class="sidebar_icon fas fa-desktop"></i>
              <p>
                Админ панель
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/profile">
              <i class="sidebar_icon fas fa-user"></i>
              <p>
                Мои данные
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/country">
              <i class="sidebar_icon fas fa-city"></i>
              <p>
                Страны
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/employee">
              <i class="sidebar_icon fas fa-users"></i>
              <p>
                Пользователи
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/position">
              <i class="sidebar_icon fas fa-star"></i>
              <p>
                Должность
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/vedgroup">
              <i class="sidebar_icon fas fa-file"></i>
              <p>
                ВЭД группы
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/customs-import-export">
              <i class="sidebar_icon fas fa-file-excel"></i>
              <p>
                Таможня  (Excel)
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/report-by-country">
              <i class="sidebar_icon fas fa-file-excel"></i>
              <p>
                Отчет по странам
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/report-by-product">
              <i class="sidebar_icon fas fa-file-excel"></i>
              <p>
                Отчет по продуктам
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/region">
              <i class="sidebar_icon fas fa-globe"></i>
              <p>
                Области
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/crm/bus">
              <i class="sidebar_icon fas fa-bus"></i>
              <p>
                Автобус
              </p>
            </router-link>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <span class="sidebar_icon fas fa-users-cog" style="font-size: 20px;"></span>
              <p>
                Setting users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link class="nav-link" to="/crm/role">
                  <p>
                    Role
                  </p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/crm/conts">
                  <p>
                    Controller
                  </p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link class="nav-link" to="/crm/action">
                  <p>
                    Actions
                  </p>
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
    <strong>Copyright &copy; 2020 <a href="#">MIRXAN </a>.</strong>
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
  .brand-link.active{
    border-bottom: 1px solid #3e6174;
  }
  .brand-text img{
    width: 100%;
  }
  .sidebar{
    padding-left: 0px;
    padding-right: 0px;
  }
  .sidebar_icon{
    font-size: 18px;
    color: #3e6174;
    margin-right: 5px;
  }
  .content-wrapper{
    background: #ebf2f6;
    padding-top: 30px;
  }
  .nav-sidebar .nav-item>.nav-link{
    color: #3e6174;
  }
  .nav-sidebar .nav-item>.nav-link:hover,
  .nav-pills .nav-link.active,
  .nav-pills .show > .nav-link{
    background-color: #e6e6e6;
    font-weight: bold;
  }
  .nav-pills .nav-link{
    border-radius: 0px;
  }
</style>
