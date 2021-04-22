<template>
  <form @submit.prevent.enter="saveAction" enctype="multipart/form-data" class="add_action">
    <div class="page_header">
      <h4 class="header_title">Топшириқ яратиш</h4>
      <div class="d-flex">
        <div class="mr_15">
                <date-picker
                :class="isRequired(form.date) ? 'isRequired' : ''"
                v-model="form.date"
                placeholder="Сана"
                value-type="format"
                format="DD.MM.YYYY"
                ></date-picker>
            </div>
        <router-link class="btn_black" to="/crm/dailyjob"
            ><i data-feather="arrow-left" class="sidebar_icon"></i>
            Орқага</router-link>
      </div>

    </div>
    <div class="jv_card">
        <div class="row align-items-end task_add_block">
            <div class="col-lg-12">
              <label for="text" class="title_label">Хисобот матни</label>
              <vue-editor id="text" v-model="form.text" />
            </div>
          <div class="form_btn_block">
            <button type="submit" class="btn_green">
              <i class="sidebar_icon" data-feather="save"></i>
              Сақлаш
            </button>
          </div>
        </div>
    </div>
  </form>
</template>

<script>
import Multiselect from "vue-multiselect";
import { mapActions, mapGetters } from "vuex";
import DatePicker from "vue2-datepicker";
import { VueEditor } from "vue2-editor";
export default {
  components: {
    Multiselect,
    DatePicker,
    VueEditor,
  },
  data() {
    return {
      form: {
        text: "",
        date: "",
      },
      requiredInput: false,
      isLoading: false,
    };
  },
  computed: {
    ...mapGetters("dailyjob", ["getJob", 'getMassage']),
  },
  updated() {
    feather.replace();

  },
  methods: {
    ...mapActions("dailyjob", ["actionEditJob", 'actionUpdateJob']),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    async saveAction() {
      if (
        this.form.text != "" &&
        this.form.date != ""
      ) {
        await this.actionUpdateJob(this.form);
        if (this.getMassage.success) {
          toast.fire({
            type: "success",
            icon: "success",
            title: "Хисобот янгиланди!",
          });
          this.$router.push("/crm/dailyjob");
          this.requiredInput = false;
        } else {
          toast.fire({
            type: "error",
            icon: "error",
            title: "Хисобот яратишда хатолик мавжуд.",
          });
        }
      } else {
        this.requiredInput = true;
      }
    },
  },
  async mounted() {
    await this.actionEditJob({id: this.$route.params.jobId})
    this.form = this.getJob
    this.form.date = this.$g.getDate(this.getJob.date)
    feather.replace();
  },
};
</script>
<style scoped>
.multiselect__tags {
  min-height: 40px;
  display: block;
  padding: 8px 40px 0 8px;
  border-radius: 5px;
  border: 1px solid #e8e8e8;
  background: #fff;
  font-size: 14px;
}
</style>
