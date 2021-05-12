<template>
  <div class="action">
    <div class="page_header">
      <h4 class="header_title">Фойдаланувчилар</h4>
      <router-link class="btn_black" to="/crm/users"
        ><i data-feather="arrow-left" class="sidebar_icon"></i>
        Орқага</router-link
      >
    </div>
    <div class="jv_card">
      <form @submit.prevent.enter="saveAction">
        <div class="row align-items-end">
          <div class="input_style col-md-3">
            <select
              id="structure_id"
              v-model="form.structure_id"
              class="form-control input_style"
              :class="isRequired(form.structure_id) ? 'isRequired' : ''"
              required
            >
              <option value="" selected disabled>Бошқармани танланг</option>
              <option v-for="(structure, index) in getStructureList.result" :value="structure.id">
                {{ structure.name }}
              </option>
            </select>
            <label for="structure_id ">Бошқарма</label>
          </div>
          <div class="input_style col-md-3">
            <select
              id="p_id"
              v-model="form.p_id"
              class="form-control input_style"
              :class="isRequired(form.p_id) ? 'isRequired' : ''"
              required
            >
              <option value="" selected disabled>Лавозимни танланг</option>
              <option v-for="(pos, index) in getPositionList.result" :value="pos.id">
                {{ pos.name }}
              </option>
            </select>
            <label for="p_id ">Лавозим</label>
          </div>
          <div class="input_style col-md-3">
            <input
              type="text"
              class="form-control input_style"
              id="uname"
              v-model="form.name"
              required
              :class="isRequired(form.name) ? 'isRequired' : ''"
            />
            <label for="uname">Исм</label>
          </div>
          <div class="input_style col-md-3">
            <input
              type="text"
              class="form-control input_style"
              id="surename"
              v-model="form.surename"
              required
              :class="isRequired(form.surename) ? 'isRequired' : ''"
            />
            <label for="surename">Фамилия</label>
          </div>
          <div class="input_style col-md-3">
            <input
              type="email"
              class="form-control input_style"
              id="email"
              v-model="form.email"
              required
              :class="isRequired(form.email) ? 'isRequired' : ''"
            />
            <label for="email">Электрон почта</label>
          </div>
          <div class="input_style col-md-3">
            <input
              type="password"
              class="form-control input_style"
              id="password"
              v-model="form.password"
              required
              :class="isRequired(form.password) ? 'isRequired' : ''"
            />
            <label for="password">Пароль</label>
          </div>
          <div class="input_style col-md-3">
            <input
              type="password"
              class="form-control input_style"
              id="confirm_password"
              v-model="form.confirm_password"
              required
              :class="isRequired(form.confirm_password) ? 'isRequired' : ''"
            />
            <label for="confirm_password">Парольни тасдиқлаш</label>
          </div>
          <div class="input_style col-md-3">
            <select
              id="gender"
              v-model="form.gender"
              class="form-control input_style"
              :class="isRequired(form.gender) ? 'isRequired' : ''"
              required
            >
              <option value="man">Эркак</option>
              <option value="woman">Aёл</option>
            </select>
            <label for="gender">Жинс</label>
          </div>
          <div class="input_style col-md-3">
            <select
              id="role_id"
              v-model="form.role_id"
              class="form-control input_style"
              :class="isRequired(form.role_id) ? 'isRequired' : ''"
              required
            >
              <option value="" selected disabled>Ролни танланг</option>
              <option v-for="(role, index) in getRoleList" :value="role.id">
                {{ role.label }}
              </option>
            </select>
            <label for="role_id">Рол</label>
          </div>
          <div class="input_style col-md-3">
            <select
              id="gender"
              v-model="form.status"
              class="form-control input_style"
              :class="isRequired(form.status) ? 'isRequired' : ''"
              required
            >
              <option value="active">Фаол</option>
              <option value="inactive">Фаол эмас</option>
            </select>
            <label for="gender">Холати</label>
          </div>
          <div class="form_btn_block">
            <button type="submit" class="btn_green">
              <i class="sidebar_icon" data-feather="save"></i>
              Сақлаш
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data() {
    return {
      form: {
        name: "",
        surename: "",
        email: "",
        password: "",
        confirm_password: "",
        gender: "man",
        role_id: "",
        structure_id: "",
        p_id: "",
        status: "active",
      },
    };
  },
  async mounted() {
    await this.actionRoleList();
    await this.actionStructureList();
    feather.replace();
  },
  watch:{
    'form.structure_id': async function(val) {
        await this.actionPositionList({structure_id: val})
    }
  },
  computed: {
    ...mapGetters("users", ["getMessage", "getStructureList", "getPositionList"]),
    ...mapGetters("role", ["getRoleList"]),
  },
  methods: {
    ...mapActions("users", ["ActionAddUser", "actionStructureList", "actionPositionList"]),
    ...mapActions("role", ["actionRoleList"]),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    async saveAction() {
      if (
        this.form.name != "" &&
        this.form.surename != "" &&
        this.form.email != "" &&
        this.form.password != "" &&
        this.form.confirm_password != "" &&
        this.form.gender != "" &&
        this.form.role_id != "" &&
        this.form.structure_id != "" &&
        this.form.p_id != "" &&
        this.form.status != ""
      ) {
        await this.ActionAddUser(this.form);
        if (this.getMessage.success) {
            toast.fire({
                type: "success",
                icon: "success",
                title: this.getMessage.message,
            });
            this.$router.push("/crm/users");
            this.requiredInput = false;
        } else {
            if (typeof this.getMessage.message == "object") {
                Object.values(this.getMessage.message).forEach((element) => {
                    toast.fire({
                        type: "error",
                        icon: "error",
                        title: element[0],
                    });
                });
            }else {
                toast.fire({
                    type: "error",
                    icon: "error",
                    title: this.getMessage.message,
                });
            }
        }
      } else {
        this.requiredInput = true;
      }
    },
  },
};
</script>
<style scoped>
.input_style {
  margin-bottom: 15px;
}
</style>
