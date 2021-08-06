<template>
  <div class="edit_action">
    <div class="page_header">
		<h4 class="header_title">
			Edit Task
		</h4>
        <router-link class="btn_black" to="/crm/state"><i data-feather="arrow-left" class="sidebar_icon"></i> Назад</router-link>
	</div>
    <div class="jv_card">
        <form @submit.prevent.enter="saveAction">
          <div class="row">
            <div class="col-md-4">
                <multiselect
                    v-model="selectedRegion"
                    :options="getRegionList"
                    :custom-label="nameWithLang"
                    placeholder="Выберите Region"
                    selectLabel="Нажмите Enter, чтобы выбрать"
                    deselectLabel="Нажмите Enter, чтобы удалить"
                    :class="isRequired(selectedRegion) ? 'isRequired' : ''"
                    :allow-empty="false"
                    label="name"
                    track-by="name"></multiselect>
            </div>
			  <div class="input_style col-md-4">
			    <input
			    	type="text"
			    	class="form-control input_style"
			    	id="contName"
			    	v-model="form.name"
                    required
			    	:class="isRequired(form.name) ? 'isRequired' : ''"
				>
			    <label for="contName">State Name</label>
			</div>
            <div class="form-group col-lg-3 form_btn">
              <button type="submit" class="btn_green">
                <i class="sidebar_icon" data-feather="save"></i>
                Сохранить
              </button>
            </div>
          </div>
        </form>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import { mapActions, mapGetters } from "vuex";
export default {
  components: {
    Multiselect,

  },
  data() {
    return {
		form:{
			name:'',
			region_id:'',
		},
        selectedRegion: null,
		requiredInput:false,
		isLoading: false,
        findController: {},
    };
  },
  computed: {
    ...mapGetters("mystate", ["getStates", "getMassage", "getState"]),
    ...mapGetters('region',['getRegionList']),
  },
  async mounted() {
    let data = {
      id: this.$route.params.stateId
    };
    await this.actionEditState(data);
    await this.actionRegionList(data);
    this.selectedRegion = this.getRegionList.find(item => item.id == this.getState.region_id)
    feather.replace()
	this.form = this.getState;
  },
  methods: {
    ...mapActions("State", ["actionEditState", "actionStates", "actionUpdateState"]),
    ...mapActions('region',['actionRegionList']),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    nameWithLang ({ name}) {
        return `${name}`
    },
    async saveAction() {
      if (this.form.name != "" && this.form.name != null && this.selectedRegion != null && this.selectedRegion != '') {
		await this.actionUpdateState(this.form);
        if(this.getMassage.success){
            await this.actionStates()
            toast.fire({
                type: 'success',
                icon: 'success',
                title: this.getMassage.message,
            })
            this.$router.push("/crm/state");
        }else{
            toast.fire({
                type: 'error',
                icon: 'error',
                title: this.getMassage.message,
            })
        }
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
