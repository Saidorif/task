<template>
  <div class="action">
    <div class="page_header">
      <h4 class="header_title">Топшириқлар</h4>
      <div class="d_flex">
        <button type="button" class="btn_red mr_15" v-if="getTaskList.length"  @click="$g.toWord('word', $g.getDate(new Date()))">
          <i data-feather="file-text" class="sidebar_icon"></i> Word версия
        </button>
        <router-link class="btn_green" to="/crm/tasks/add"
          ><i data-feather="plus" class="sidebar_icon"></i>Топшириқ
          яратиш</router-link
        >
      </div>
    </div>
    <div class="jv_card filter">
      <div class="input_style col-md-3">
        <date-picker
          range
          v-model="filter_date"
          placeholder="Оралиқ сана"
          value-type="format"
          format="DD.MM.YYYY"
        ></date-picker>
      </div>
      <div class="input_style col-md-3 mr_15">
        <select v-model="filter.status" id="status">
          <option value="">Холатни танланг</option>
          <option value="rejected">Рад этилган</option>
          <option value="accepted">Қабул қилинган</option>
          <option value="pending">Тасдиқланмаган</option>
          <option value="active">Бажарилмоқда</option>
          <option value="draft">Режада</option>
        </select>
        <label for="status">Холат</label>
      </div>
      <button type="button" class="btn_black mr_15" @click="filterDate">
        <i data-feather="filter" class="sidebar_icon"></i> Саралаш
      </button>
      <button type="button" class="btn_yellow" @click="clearFilter">
        <i data-feather="wind" class="sidebar_icon"></i> Тозалаш
      </button>
    </div>
    <div class="jv_card">
      <div class="table-responsive">
        <table
          class="table table-bordered text-center table-hover table-striped"
        >
          <thead>
            <tr>
              <th scope="col" style="width: 40px">№</th>
              <th scope="col">Қисқа мазмуни</th>
              <th scope="col" style="width: 30%">Масъул ижрочи</th>
              <th scope="col" style="width: 100px">Муддати</th>
              <th scope="col" style="width: 140px">Жорий холати</th>
              <th scope="col" style="width: 110px">Тахрирлаш</th>
            </tr>
          </thead>
          <tbody
            v-if="getTasks.data && getTasks.data.length"
            class="hide_users"
          >
            <tr v-for="(cont, index) in getTasks.data">
              <td style="width: 40px" scope="row">{{ index + 1 }}</td>
              <td>{{ cont.title }}</td>
              <td style="padding: 0px">
                <ul class="userList">
                  <li
                    v-for="item in cont.users"
                    @click="showAllUsers(cont)"
                    :class="item.svot == 1 ? 'active' : ''"
                    v-tooltip.top-center="
                      '<b>' +
                      item.user.position.name +
                      '</b><br> ' +
                      item.user.name +
                      ' ' +
                      item.user.surename
                    "
                  >
                    {{ item.user.position.structure.name }} {{ cont.isOpen }}
                  </li>
                </ul>
              </td>
              <td>{{ $g.getDate(cont.exp_date) }}</td>
              <td>
                <span
                  class="alert alert-danger jv_alert"
                  v-if="cont.status == 'rejected'"
                  >Рад этилган</span
                >
                <span
                  class="alert alert-success jv_alert"
                  v-if="cont.status == 'accepted'"
                  >Қабул қилинган</span
                >
                <span
                  class="alert alert-warning jv_alert"
                  v-if="cont.status == 'pending'"
                  >Тасдиқланмаган</span
                >
                <span
                  class="alert alert-info jv_alert"
                  v-if="cont.status == 'active'"
                  >Бажарилмоқда</span
                >
                <span
                  class="alert alert-dark jv_alert"
                  v-if="cont.status == 'draft'"
                  >Режада</span
                >
              </td>
              <td>
                <button
                  tag="button"
                  class="btn_green_icon"
                  v-tooltip.top-center="'Долзарб'"
                  data-bs-toggle="modal" data-bs-target="#exampleModal"
                >
                  <i data-feather="flag" class="sidebar_icon"></i>
                  <!-- <i data-feather="slash" class="sidebar_icon"></i> -->
                </button>
                <router-link
                  tag="button"
                  class="btn_blue_icon"
                  :to="`/crm/tasks/edit/${cont.id}`"
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
            :data="getTasks"
            @pagination-change-page="getResults"
          ></pagination>
        </table>
      </div>
    </div>
    <div id="word" v-if="getTaskList.length">
      <div class="landscape_A4_page">
        <h6>
          Ўзбекистон Республикаси Транспорт вазирлигига юклатилган муҳим
          топшириқлар ижроси тўғрисида <br />
          М А Ъ Л У М О Т
        </h6>
        <table>
          <thead>
            <tr>
              <th>№</th>
              <th>Топшириқ мазмуни</th>
              <th>
                <span style="color: rgb(192, 0, 0)">{{
                  $g.getDate(new Date())
                }}</span>
                йил <br />
                ҳолатига бажарилган ишлар
              </th>
              <th>Ижро муддати</th>
              <th style="border-right: none">Масъул ижрочилар</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(elem, ind) in getTaskList">
              <td>{{ind+1}}</td>
              <td>{{ elem.title }}</td>
              <td>
                <template v-for="(item, index) in elem.users">
                  <div v-for="(ans, ind) in item.items" v-html="ans.text"></div>
                </template>
              </td>
              <td style="text-align: center">
                {{ $g.getDate(elem.exp_date) }}
              </td>
              <td style="border-right: none">
                <p
                  v-for="(user, index) in  elem.users"
                  :class="{ selected: user.svot }"
                >
                  {{ user.user.name.charAt(0) }}. {{ user.user.surename }},
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" @submit.prevent.enter="importantTask">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Долзарблилик сабаби</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input_style">
                        <textarea name="" v-model="important.text" id="comment" class="input_style"  cols="30" rows="10" required></textarea>
                        <label for="comment">Сабаб</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Сақлаш</button>
                </div>
            </form>
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
      filter: {
        status: "",
        date_from: "",
        date_to: "",
        download: "",
      },
      important: {
          id: '',
          text: ''
      },
      filter_date: "",
    };
  },
  async mounted() {
    await this.actionTasks({ page: 1, filter: this.filter });
    feather.replace();
    $(".userList").on("click", function () {
      $(this).toggleClass("active");
    });
  },
  updated() {
    feather.replace();
  },
  computed: {
    ...mapGetters("task", ["getTasks", "getTaskMassage", "getTaskList"]),
  },
  watch: {
    filter_date: function (val) {
      if (val[0]) {
        let dfrom = val[0].split(".");
        let dto = val[1].split(".");
        this.filter.date_from = `${dfrom[2]}.${dfrom[1]}.${dfrom[0]}`;
        this.filter.date_to = `${dto[2]}.${dto[1]}.${dto[0]}`;
      } else {
        this.filter.date_from = null;
        this.filter.date_to = null;
      }
    },
  },
  methods: {
    ...mapActions("task", ["actionTasks", "actionDeleteTask"]),
    async getResults(page = 1) {
      await this.actionTasks({ page: page });
    },
    async filterDate() {
      this.filter.download = true;
      await this.actionTasks({ page: 1, filter: this.filter });
    },
    async importantTask(){

    },
    async clearFilter(){
      this.filter.download = '';
      this.filter.date_from = '';
      this.filter.date_to = '';
      this.filter.status = '';
      await this.actionTasks({ page: 1, filter: this.filter });
    },
    showAllUsers(con) {
      if (con.isOpen) {
        con.isOpen = false;
      } else {
        con.isOpen = true;
      }
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
            await this.actionDeleteTask(id);
            if (this.getTaskMassage.success) {
              await this.actionTasks(page);
              toast.fire({
                type: "success",
                icon: "success",
                title: this.getTaskMassage.message,
              });
            }
          }
        });
    },
  },
};
</script>
<style scoped>
</style>
