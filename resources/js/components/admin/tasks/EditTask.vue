<template>
  <div class="edit_action">
      <Loader v-if="isLoading" />
    <div class="page_header">
      <h4 class="header_title">Edit Task</h4>
      <router-link class="btn_black" to="/crm/tasks"
        ><i data-feather="arrow-left" class="sidebar_icon"></i>
        Назад</router-link
      >
    </div>
    <div class="jv_card">
      <form @submit.prevent.enter="saveAction" enctype="multipart/form-data">
        <div class="row align-items-end">
          <div class="col-md-5">
            <multiselect
                v-model="selectedUser"
                :options="userlist"
                :custom-label="nameWithLang"
                placeholder="Выберите User"
                selectLabel="Нажмите Enter, чтобы выбрать"
                :multiple="false"
                deselectLabel="Нажмите Enter, чтобы удалить"
                :class="isRequired(selectedUser) ? 'isRequired' : ''"
                :allow-empty="false"
                label="name surename"
                :internal-search="false"
                :close-on-select="false"
                track-by="name" @search-change="asyncFind">
                    <!-- <template slot="tag" slot-scope="{ option, remove }">
                        <span class="multiselect__tag" :class="option.svot ? 'selected' : ''">
                            <span @click="selectSvot(option)">{{ option.name }} {{ option.surename }}</span>
                            <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                        </span>
                    </template> -->
                        <template slot="singleLabel" slot-scope="props">
                            <span class="option__desc">
                                <span class="option__title">{{ props.option.name }} {{ props.option.surename }}</span>
                            </span>
                        </template>
                        <template slot="option" slot-scope="props">
                            <div class="option__desc">
                                <b class="option__title">{{ props.option.name }} {{ props.option.surename }} </b> <br>
                                <small class="option__small">{{ props.option.position.structure.name }} - <i>{{ props.option.position.name }}</i> </small>

                            </div>
                        </template>
                </multiselect>
          </div>
          <div class="input_style col-md-5">
            <input
              type="text"
              class="form-control input_style"
              id="contName"
              v-model="form.title"
              required
              :class="isRequired(form.title) ? 'isRequired' : ''"
            />
            <label for="contName">Title</label>
          </div>
          <div class="col-md-2">
            <date-picker
              v-model="form.exp_date"
              placeholder="Выберите срок"
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
                  <th>Ф.И.О</th>
                  <th>Управление</th>
                  <th>Должность</th>
                  <th>Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(user, index) in selectedUsersList"
                  :class="{ selected: user.svot}"
                >
                  <td>{{ index + 1 }}</td>
                  <td>
                    {{ user.name }} {{ user.surename }} {{ user.lastname }}
                  </td>
                  <td>{{user.position.structure.name}}</td>
                  <td>{{ user.position.name }}</td>
                  <td>
                    <button
                      type="button"
                      class="btn_blue_icon"
                      @click="svotUser(user, index)"
                      v-if="!hasSvot || user.svot"
                    >
                      <i data-feather="check-square" class="sidebar_icon"></i>
                    </button>
                    <button
                      type="button"
                      class="btn_red_icon"
                      @click="deleteUser(index)"
                    >
                      <i data-feather="trash" class="sidebar_icon"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <template v-for="(item, index) in allItems">
            <div class="col-md-9 mt-5">
              <label for="text" class="title_label">Text</label>
              <div class="disabled_custom_editor" id="text" v-html="item.text" ></div>
            </div>
            <div class="col-md-3">
                <a :href="item.file" v-if="typeof item.file == 'string'" class="btn_black  col-md-5" download=""><i class="sidebar_icon" data-feather="download"></i>Download</a>
            </div>
          </template>
            <!-- <template v-for="(item, index) in allItems" v-if="form.status != 'active'"> -->
                <div class="col-md-9 mt-5">
                <label for="text" class="title_label">Text</label>
                <vue-editor id="text" v-model="newItem.text" />
                </div>
                <div class="col-md-3">
                <div class="input_style_file">
                    <label for="file" id="inputFileLabeld"
                    >File Upload</label
                    >
                    <input
                    type="file"
                    id="file"
                    @change="
                        inputFileUpload($event, 'inputFileLabeld', newItem)
                    "
                    />
                </div>
                </div>
            <!-- </template> -->
          <div class="form_btn_block">
            <button
                type="button"
                class="btn_blue mr_15"
                @click="saveAction('active')"
                v-if="form.status != 'active'"
            >
                <i class="sidebar_icon" data-feather="send"></i>
                Опубликовать
            </button>
            <button type="submit" class="btn_green">
              <i class="sidebar_icon" data-feather="save"></i>
              Обновить
            </button>
          </div>
        </div>
      </form>
    </div>
    <h2>Topshiriq bo'yicha bajarilgan ishlar</h2>
    <div class="jv_card" v-if="getTask.users" >
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation" v-for="(item, index) in getTask.users">
                <button class="nav-link" :class="index == 0 ? 'active' : ''"
                    :id="'home-tab'+index"
                    data-bs-toggle="tab"
                    :data-bs-target="'#home'+index"
                    type="button" role="tab"
                    :aria-controls="'home'+index" :aria-selected="true">{{item.user.name}} {{item.user.surename}}</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade"  :class="index == 0 ? 'show active' : ''"
            :id="'home'+index" role="tabpanel" :aria-labelledby="'home-tab'+index" v-for="(item, index) in getTask.users">
                <div class="table-responsive mt-4" v-if="item.items.length">
                    <table
                    class="table table-bordered text-center table-hover table-striped"
                    >
                    <thead>
                        <tr>
                            <th>sana</th>
                            <th>Xisobot matni</th>
                            <th>file</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr  v-for="(ans, ind) in item.items">
                                <td>{{ $g.getDate(ans.created_at) }}</td>
                                <td> <div v-html="ans.text"></div> </td>
                                <td>
                                    <a :href="'/'+ans.file" v-if="ans.file" class="btn_blue_icon" download="">
                                        <i class="sidebar_icon" data-feather="download"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import { mapActions, mapGetters } from "vuex";
import DatePicker from "vue2-datepicker";
import { VueEditor } from "vue2-editor";
import Loader from "../../Loader";
export default {
  components: {
    Multiselect,
    DatePicker,
    VueEditor,
    Loader
  },
  data() {
    return {
        form: {
            title: "",
            items: [],
            exp_date: "",
            users: [],
            status: '',
        },
        allItems: [{ text: "", file: null }],
        newItem: { text: "", file: null },
        userlist: [],
        selectedUsersList: [],
        requiredInput: false,
        isLoading: true,
        selectedUser: null,
        hasSvot: false,
    };
  },
  computed: {
    ...mapGetters("task", ["getMassage", 'getTask']),
    ...mapGetters("user", ["getUserList"]),
  },
  watch: {
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
        this.selectedUsersList.push(val);
      }
    },
  },
  updated() {
    feather.replace();
  },
  async mounted() {
    await this.rerenderData();
    this.isLoading = false
  },
  methods: {
    ...mapActions("task", ["actionEditTask", "actionUpdateTask"]),
    ...mapActions("user", ["ActionUserList"]),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    deleteItem(index) {
      this.allItems.splice(index, 1);
    },
    nameWithLang({ name, surename, lastname, position }) {
        return `${position.name} ${name} ${surename} ${lastname}`
    },
    async rerenderData(){
        let data = {
        id: this.$route.params.taskId,
        };
        await this.ActionUserList();
        await this.actionEditTask(data);
        this.userlist = this.getUserList;
        this.form.title = this.getTask.title
        this.form.status = this.getTask.status
        this.form.exp_date = this.$g.getDate(this.getTask.exp_date)
        this.selectedUsersList = this.getTask.users.map(item => {
                let data = item.user
                data.svot = item.svot
                if(item.svot == 1){
                    this.hasSvot = true
                }
                return data
        })
        this.allItems = this.getTask.items
        feather.replace();
    },
    deleteUser(ind) {
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
      document.querySelector("#" + labelId).innerHTML = name;
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
        return el.name.toLowerCase().indexOf(trval.toLowerCase()) > -1;
      });
    },
    async saveAction(sts) {
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
        formData.append("id", this.$route.params.taskId);
        formData.append("exp_date", this.form.exp_date);
        if(sts){
            formData.append("status", 'active');
        }else{
            formData.append("status", 'draft');
        }
        this.form.users.forEach((item, index) => {
          formData.append(`users[${index}][user_id]`, item.user_id);
          formData.append(`users[${index}][svot]`, item.svot);
        });
        if(this.newItem.text != ''){
            this.allItems.push(this.newItem)
        }
        this.allItems.forEach((item, index) => {
          formData.append(`items[${index}][text]`, item.text);
          if(item.id){
              formData.append(`items[${index}][id]`, item.id);
          }
          if(typeof item.file == 'object' && item.file != null){
            formData.append(`items[${index}][file]`, item.file);
          }
        });
            this.isLoading = true
        await this.actionUpdateTask({id: this.$route.params.taskId, data: formData});
        if (this.getMassage.success) {
            this.rerenderData()
            this.newItem.text = ''
            this.newItem.file = null
          toast.fire({
            type: "success",
            icon: "success",
            title: "Task обновлено!",
          });
          this.requiredInput = false;
        } else {
          toast.fire({
            type: "error",
            icon: "error",
            title: "Такой Task уже существует!",
          });
        }
                this.isLoading = false

      } else {
        this.requiredInput = true;
      }
    },
  },
};
</script>
<style scoped>
</style>
