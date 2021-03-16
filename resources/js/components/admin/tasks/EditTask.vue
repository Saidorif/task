<template>
  <div class="edit_action">
    <div class="card">
      <div class="card-header">
        <h4 class="title_user">
          <i class="nav-icon pe-7s-box1"></i>
          Edit Action
        </h4>
        <router-link class="btn btn-primary" to="/crm/category">
          <i class="fas fa-arrow-circle-left"></i> Back
        </router-link>
      </div>
      <div class="card-body">
        <form @submit.prevent.enter="saveAction">
          <div class="row">
            <div class="form-group col-md-9">
              <label for="contName">Название контроллера</label>
              <multiselect
                :value="findController"
                :options="getContList"
                @search-change="value => filterController(value)"
                v-model="findController"
                placeholder="Выберите"
                :searchable="true"
                track-by="id"
                label="name"
                :max="3"
                :loading="isLoading"
                selectLabel="Нажмите Enter, чтобы выбрать"
                deselectLabel="Нажмите Enter, чтобы удалить"
                @select="selectedItem"
                :class="isRequired(form.conts_id) ? 'isRequired' : ''"
              >
                <span slot="noResult">По вашему запросу ничего не найдено</span>
                <span slot="noOptions">Cписок пустой</span>
              </multiselect>
            </div>
            <div class="form-group col-md-9">
              <label for="actionName">Action Name</label>
              <input
                type="text"
                class="form-control input_style"
                id="actionName"
                placeholder="Action Name"
                v-model="form.name"
                :class="isRequired(form.name) ? 'isRequired' : ''"
              />
            </div>
            <div class="form-group col-md-9">
              <label for="contName">Code</label>
              <input
                type="text"
                class="form-control input_style"
                id="contName"
                placeholder="Code"
                v-model="form.code"
                :class="isRequired(form.code) ? 'isRequired' : ''"
              />
            </div>
            <div class="form-group col-lg-3 form_btn">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Сохранить
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import { mapActions, mapGetters } from "vuex";
export default {
  components: {
    Multiselect
  },
  data() {
    return {
      form: {
        id: null,
		name:'',
		code:'',
		conts_id:'',
      },
      requiredMessage: null,
      requiredInput: false,
      isLoading: false,
      findController: {}
    };
  },
  computed: {
    ...mapGetters("action", ["getActions", "getMassage", "getAction"]),
    ...mapGetters("conts", ["getContList"])
  },
  async mounted() {
    let data = {
      id: this.$route.params.actionId
    };
    await this.actionEditAction(data);
	this.form = this.getAction;
	this.findController = this.getAction.controller
  },
  methods: {
    ...mapActions("action", ["actionEditAction", "actionActions", "actionUpdateAction"]),
    ...mapActions("conts", ["actionContsFind"]),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    filterController(value) {
      if (value != "") {
        this.isLoading = true;
        setTimeout(() => {
          this.actionContsFind({ name: value });
          this.isLoading = false;
        }, 1000);
      }
    },
    selectedItem(selectedOption, id) {
      this.findController = selectedOption;
      this.form.conts_id = this.findController.id;
    },
    async saveAction() {
      if (this.form.name != "" && this.form.name != null) {
		await this.actionUpdateAction(this.form);
		await this.actionActions()
        this.$router.push("/crm/action");
        this.requiredInput = false;
      } else {
        this.requiredInput = true;
      }
    }
  }
};
</script>
<style scoped>
</style>