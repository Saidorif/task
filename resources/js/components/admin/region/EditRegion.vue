<template>
  <div class="edit_action">
    <div class="page_header">
		<h4 class="header_title">
			Edit Task
		</h4>
        <router-link class="btn_black" to="/crm/region"><i data-feather="arrow-left" class="sidebar_icon"></i> Назад</router-link>
	</div>
    <div class="jv_card">
        <form @submit.prevent.enter="saveAction">
          <div class="row">
			<div class="input_style col-md-4">
			  <input
			  	type="text"
			  	class="form-control input_style"
			  	id="contName"
			  	v-model="form.name"
                  required
			  	:class="isRequired(form.name) ? 'isRequired' : ''"
				>
			  <label for="contName">Region Name</label>
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
    ...mapGetters("region", ["getRegions", "getMassage", "getRegion"]),
  },
  async mounted() {
    let data = {
      id: this.$route.params.regionId
    };
    await this.actionEditRegion(data);
    feather.replace()
	this.form = this.getRegion;
  },
  methods: {
    ...mapActions("region", ["actionEditRegion", "actionRegions", "actionUpdateRegion"]),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    async saveAction() {
      if (this.form.name != "" && this.form.name != null) {
		await this.actionUpdateRegion(this.form);
        if(this.getMassage.success){
            await this.actionRegions()
            toast.fire({
                type: 'success',
                icon: 'success',
                title: this.getMassage.message,
            })
            this.$router.push("/crm/region");
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
