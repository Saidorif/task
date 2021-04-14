<template>
  <div class="add_action">
    <div class="page_header">
      <h4 class="header_title">Топшириқ яратиш</h4>
      <router-link class="btn_black" to="/crm/tasks"
        ><i data-feather="arrow-left" class="sidebar_icon"></i>
        Орқага</router-link
      >
    </div>
    <div class="jv_card">
      <form @submit.prevent.enter="saveAction" enctype="multipart/form-data">
        <div class="row align-items-end task_add_block">
          <div class="select_serach">
            <multiselect
              v-model="selectedStr"
              :options="getStructureList"
              placeholder="Ижрочи бошқармани танланг"
              :multiple="false"
              :allow-empty="false"
              label="name"
              :internal-search="true"
              track-by="name"
            >
            </multiselect>
          </div>
          <div class="select_serach">
            <multiselect
              v-model="selectedUser"
              :options="userlist"
              :custom-label="searchWithNameSelect"
              placeholder="Ижрочини танланг"
              :multiple="false"
              :class="isRequired(selectedUser) ? 'isRequired' : ''"
              :allow-empty="false"
              label="name surename"
              :internal-search="false"
              :close-on-select="false"
              track-by="name"
              @search-change="asyncFind"
            >
              <template slot="singleLabel" slot-scope="props">
                <span class="option__desc">
                  <span class="option__title"
                    >{{ props.option.name }} {{ props.option.surename }}</span
                  >
                </span>
              </template>
              <template slot="option" slot-scope="props">
                <div class="option__desc">
                  <b class="option__title"
                    >{{ props.option.name }} {{ props.option.surename }}
                  </b>
                  <br />
                  <small class="option__small"
                    >{{ props.option.position.structure.name }} -
                    <i>{{ props.option.position.name }}</i>
                  </small>
                </div>
              </template>
            </multiselect>
          </div>
          <div class="col-md-2">
            <date-picker
              :disabled-date="disabledStartDate"
              :class="isRequired(form.exp_date) ? 'isRequired' : ''"
              v-model="form.exp_date"
              placeholder="Ижро муддати"
              value-type="format"
              format="DD.MM.YYYY"
            ></date-picker>
          </div>
          <div class="table-responsive mt-5" v-if="selectedUsersList.length">
            <table
              class="table table-bordered text-center table-hover table-striped"
            >
              <thead>
                <tr>
                  <th>№</th>
                  <th>Ф.И.Ш</th>
                  <th>Бошқарма</th>
                  <th>Лавозими</th>
                  <th>Тахрирлаш</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(user, index) in selectedUsersList"
                  :class="{ selected: user.svot }"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    {{ user.name }} {{ user.surename }} {{ user.lastname }}
                  </td>
                  <td>{{ user.position.structure.name }}</td>
                  <td>{{ user.position.name }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn_blue_icon"
                      @click="svotUser(user, index)"
                      v-if="!hasSvot || user.svot"
                      v-tooltip.top-center="'Свотни белгилаш'"
                    >
                      <i data-feather="user-check" class="sidebar_icon"></i>
                    </button>
                    <button
                      type="button"
                      class="btn_red_icon"
                      @click="deleteUser(index)"
                      v-tooltip.top-center="'Ижрочини ўчириш'"
                    >
                      <i data-feather="trash" class="sidebar_icon"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="input_style col-md-12 mt-5 mb-4">
            <input
              type="text"
              class="form-control input_style"
              id="contName"
              v-model="form.title"
              required
              :class="isRequired(form.title) ? 'isRequired' : ''"
            />
            <label for="contName">Қисқача мазмуни</label>
          </div>
          <template v-for="(item, index) in allItems">
            <div class="col-lg-9">
              <label for="text" class="title_label">Тўлиқ матни</label>
              <vue-editor id="text" v-model="item.text" />
            </div>
            <div class="col-lg-3">
              <button
                type="button"
                class="btn_red_icon"
                v-if="index > 0"
                @click="deleteItem(index)"
                style="margin-bottom: 70px"
              >
                <i data-feather="trash" class="sidebar_icon"></i>
              </button>
              <div class="input_style_file">
                <label for="file" :id="'inputFileLabel' + index"
                  >Файл юклаш</label
                >
                <input
                  type="file"
                  id="file"
                  @change="
                    inputFileUpload($event, 'inputFileLabel' + index, item)
                  "
                />
              </div>
            </div>
          </template>
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
        title: "",
        items: [],
        exp_date: "",
        users: [],
      },
      allItems: [{ text: "", file: "" }],
      userlist: [],
      selectedUsersList: [],
      requiredInput: false,
      isLoading: false,
      selectedUser: null,
      selectedStr: null,
      hasSvot: true,
    };
  },
  computed: {
    ...mapGetters("task", ["getTaskMassage"]),
    ...mapGetters("user", ["getUserList", "getStructureList"]),
  },
  watch: {
    selectedStr: async function(val){
        await this.ActionUserList(val.id);
        this.userlist = this.getUserList;
    },
    selectedUser: function (val) {
      val.svot = false;
      if (this.selectedUsersList.length) {
        let found = false;
        this.selectedUsersList.forEach((item) => {
          if (item.id == val.id) {
            found = true;
          }
        });
        if (!found) {
          this.selectedUsersList.push(val);
        }
      } else {
        val.svot = true;
        this.selectedUsersList.push(val);
      }
    },
  },
  updated() {
    feather.replace();
  },
  methods: {
    ...mapActions("task", ["actionAddTask"]),
    ...mapActions("user", ["ActionUserList", "ActionStructureList"]),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    disabledStartDate(date) {
      return date < new Date();
    },
    addItem() {
      let item = { text: "", file: "" };
      this.allItems.push(item);
    },
    deleteItem(index) {
      this.allItems.splice(index, 1);
    },
    searchWithNameSelect({ name, surename, lastname, position }) {
      return `${position.name} ${name} ${surename} ${lastname}`;
    },
    deleteUser(ind) {
      swal
        .fire({
          type: "confirm",
          toast: false,
          icon: "question",
          title: "Ҳақиқатан ҳам ўчиришни хоҳлайсизми?",
          confirmButtonText: `Ўчириш`,
          cancelButtonText: `Бекор қилиш`,
          showCancelButton: true,
        })
        .then(async (result) => {
          if (result.isConfirmed) {
            this.selectedUsersList.splice(ind, 1);
          }
        });
    },
    inputFileUpload(event, labelId, item) {
      if (
        !event ||
        !event.target ||
        !event.target.files ||
        event.target.files.length === 0
      ) {
        return;
      }
      const name = event.target.files[0].name;
      document.querySelector("#" + labelId).innerHTML = name
        ? name
        : "Файл юклаш";
      item.file = event.target.files[0];
    },
    svotUser(user, index) {
      user.svot = !user.svot;
      this.hasSvot = user.svot;
      Vue.set(this.selectedUsersList, index, user);
    },
    asyncFind(val) {
      let trval = cril().reverse(val);
      this.userlist = this.getUserList.filter((el) => {
        return (
          el.name.toLowerCase().indexOf(trval.toLowerCase()) > -1 ||
          el.surename.toLowerCase().indexOf(trval.toLowerCase()) > -1 ||
          el.position.structure.name
            .toLowerCase()
            .indexOf(trval.toLowerCase()) > -1
        );
      });
    },
    async saveAction() {
      if (
        this.form.title != "" &&
        this.form.exp_date != "" &&
        this.selectedUsersList.length
      ) {
        this.form.users = this.selectedUsersList.map((item) => {
          return { user_id: item.id, svot: item.svot ? 1 : 0 };
        });
        let formData = new FormData();
        formData.append("title", this.form.title);
        formData.append("exp_date", this.form.exp_date);
        this.form.users.forEach((item, index) => {
          formData.append(`users[${index}][user_id]`, item.user_id);
          formData.append(`users[${index}][svot]`, item.svot);
        });
        this.allItems.forEach((item, index) => {
          formData.append(`items[${index}][text]`, item.text);
          formData.append(`items[${index}][file]`, item.file);
        });
        await this.actionAddTask(formData);
        if (this.getTaskMassage.success) {
          toast.fire({
            type: "success",
            icon: "success",
            title: "Топшириқ яратилди!",
          });
          this.$router.push("/crm/tasks");
          this.requiredInput = false;
        } else {
          toast.fire({
            type: "error",
            icon: "error",
            title: "Ушбу топшириқ аллақачон мавжуд!",
          });
        }
      } else {
        this.requiredInput = true;
      }
    },
  },
  async mounted() {
    await this.ActionUserList();
    await this.ActionStructureList();
    this.userlist = this.getUserList;
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
