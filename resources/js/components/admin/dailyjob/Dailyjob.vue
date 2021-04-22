<template>
  <div class="action">
    <div class="page_header">
      <h4 class="header_title">Кунлик хисобот</h4>
      <div class="d_flex">
        <router-link class="btn_green" to="/crm/dailyjob/add"
          ><i data-feather="plus" class="sidebar_icon"></i>Топшириқ
          яратиш</router-link
        >
      </div>
    </div>
    <div class="jv_card">
      <div class="table-responsive">
        <table
          class="table table-bordered text-center table-hover table-striped"
        >
          <thead>
            <tr>
              <th scope="col" style="width: 40px">№</th>
              <th scope="col">Хисобот қисқача матни</th>
              <th scope="col">Хисобот санаси</th>
              <th scope="col">Тахрирлаш</th>
            </tr>
          </thead>
          <tbody
            v-if="getJobs.data && getJobs.data.length"
            class="hide_users"
          >
            <tr v-for="(cont, index) in getJobs.data">
              <td style="width: 40px" scope="row">{{ index + 1 }}</td>
              <td><router-link :to="`/crm/dailyjob/edit/${cont.id}`" class="text_link" v-html="cont.text">  </router-link></td>
              <td>{{ $g.getDate(cont.date) }}</td>
              <td>
                <router-link
                  tag="button"
                  class="btn_blue_icon"
                  :to="`/crm/dailyjob/edit/${cont.id}`"
                  v-tooltip.top-center="'Тахрирлаш'"
                >
                  <i data-feather="edit-2" class="sidebar_icon"></i>
                </router-link>
                <button
                  class="btn_red_icon"
                  @click="deleteItem(cont.id)"
                  v-tooltip.top-center="'Ўчириш'"
                >
                  <i data-feather="trash" class="sidebar_icon"></i>
                </button>
              </td>
            </tr>
          </tbody>
          <pagination
            :limit="4"
            :data="getJobs"
            @pagination-change-page="getResults"
          ></pagination>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
import DatePicker from "vue2-datepicker";
export default {
  components: {
    DatePicker,
  },
  data() {
    return {

    };
  },
  async mounted() {
    await this.actionJobs({ page: 1});
    feather.replace();
  },
  updated() {
    feather.replace();
  },
  computed: {
    ...mapGetters("dailyjob", ["getJobs"]),
  },
  methods: {
    ...mapActions("dailyjob", ["actionJobs"]),
    async getResults(page = 1) {
      await this.actionJobs({ page: page});
    },
    async deleteItem(id) {
      swal
        .fire({
          type: "confirm",
          toast: false,
          icon: "question",
          title: "Вы действительно хотите удалить?",
          confirmButtonText: `Удалить`,
          cancelButtonText: `Отмена`,
          showCancelButton: true,
        })
        .then(async (result) => {
          if (result.isConfirmed) {
            let page = 1;
            // await this.actionDeleteTask(id);
            // if (this.getTaskMassage.success) {
            //   await this.actionTasks(page);
            //   toast.fire({
            //     type: "success",
            //     icon: "success",
            //     title: this.getTaskMassage.message,
            //   });
            // }
          }
        });
    },
  },
};
</script>
<style scoped>
    .input_checkbox{
        position: relative;
    }

</style>
