<template>
  <div class="edit_action">
      <Loader v-if="isLoading" />
    <div class="page_header">
      <h4 class="header_title">Топшириқни тахрирлаш </h4>
      <div class="d-flex">
                      <button
                type="button"
                class="btn_blue mr_15"
                @click="saveAction('active')"
                v-if="form.status == 'draft'"
            >
                <i class="sidebar_icon" data-feather="send"></i>
                Юбориш
            </button>
        <button type="button" @click="$g.toWord('word', $g.getDate(new Date()))" class="btn_red mr_15"> <i data-feather="file-text" class="sidebar_icon"></i> Word версияси </button>
        <router-link class="btn_black" to="/crm/tasks"><i data-feather="arrow-left" class="sidebar_icon"></i>
        Орқага</router-link>
      </div>
    </div>
    <div class="jv_card">
      <form @submit.prevent.enter="saveAction" enctype="multipart/form-data">
        <div class="row align-items-end">
            <div class="col-md-5">
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
          <div class="col-md-5">
            <multiselect
                v-model="selectedUser"
                :options="userlist"
                :custom-label="nameWithLang"
                placeholder="Ижрочини танланг"
                :multiple="false"
                :class="isRequired(selectedUser) ? 'isRequired' : ''"
                :allow-empty="false"
                label="name surename"
                :internal-search="false"
                :close-on-select="false"
                track-by="name" @search-change="asyncFind">
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
          <div class="col-md-2">
            <date-picker
              v-model="form.exp_date"
              placeholder="Ижро муддати"
              value-type="format"
              :disabled-date="disabledStartDate"
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
            <div class="col-md-9">
              <label for="text" class="title_label">Тўлиқ матни</label>
              <div class="disabled_custom_editor" id="text" v-html="item.text" ></div>
            </div>
            <div class="col-md-3">
                <a :href="item.file" v-if="typeof item.file == 'string'" class="btn_black  col-md-5" download=""><i class="sidebar_icon" data-feather="download"></i>Юклаб олиш</a>
            </div>
          </template>
            <!-- <template v-for="(item, index) in allItems" v-if="form.status != 'active'"> -->
                <div class="col-md-9 mt-5">
                <label for="text" class="title_label">Тўлиқ матни</label>
                <vue-editor id="text" v-model="newItem.text" />
                </div>
                <div class="col-md-3">
                <div class="input_style_file">
                    <label for="file" id="inputFileLabeld"
                    >Файл юклаш</label
                    >
                    <input
                    type="file"
                    id="file"
                    @change="inputFileUpload($event, 'inputFileLabeld', newItem)"
                    />
                </div>
                </div>
            <!-- </template> -->
          <div class="form_btn_block">
            <button
                type="button"
                class="btn_red mr_15"
                data-bs-toggle="modal" data-bs-target="#exampleModal"
                v-if="form.status == 'pending'"
            >
                <i class="sidebar_icon" data-feather="slash"></i>
               Рад этиш
            </button>
            <button
                type="button"
                class="btn_blue mr_15"
                @click="acceptTask()"
                v-if="form.status == 'pending'"
            >
                <i class="sidebar_icon" data-feather="check"></i>
               Қабул қилиш
            </button>

            <button type="submit" class="btn_green">
              <i class="sidebar_icon" data-feather="save"></i>
              Сақлаш
            </button>
          </div>
        </div>
      </form>
    </div>
    <h4>Топшириқ бўйича бажарилган ишлар</h4>
    <div class="jv_card mb-5" v-if="getTask.users" >
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" style="position:relative;" role="presentation" v-for="(item, index) in getTask.users">
                <button class="nav-link" :class="index == 0 ? 'active' : ''"
                    :id="'home-tab'+index"
                    data-bs-toggle="tab"
                    :data-bs-target="'#home'+index"
                    type="button" role="tab"
                    :aria-controls="'home'+index" :aria-selected="true">{{item.user.name}} {{item.user.surename}} <span class="reads_count" v-if="item.unreads_count > 0">{{ item.unreads_count }}</span></button>
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
                            <th style="width: 100px;">Сана</th>
                            <th>Хисобот матни</th>
                            <th>Файл</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr  v-for="(ans, ind) in item.items">
                                <td style="width: 100px;">{{ $g.getDate(ans.created_at) }}</td>
                                <td> <div v-html="ans.text"></div> </td>
                                <td style="width: 100px;">
                                    <a :href="'/'+ans.file" v-if="ans.file" class="btn_blue_icon" download=""  v-tooltip.top-center="'Юклаб олиш'">
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form class="modal-dialog"   @submit.prevent.enter="cancelTask" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Рад этилганлик сабаби</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input_style">
                        <textarea name="" v-model="comment.text" id="comment" class="input_style"  cols="30" rows="10" required></textarea>
                        <label for="comment">Сабаб</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Бекор қилиш</button>
                    <button type="submit" class="btn btn-primary">Рад этиш</button>
                </div>
            </div>
        </form>
    </div>
    <div id="word"  >
        <div class="landscape_A4_page">
            <h6>
                Ўзбекистон Республикаси Транспорт вазирлигига юклатилган муҳим топшириқлар ижроси тўғрисида <br>
                М А Ъ Л У М О Т
            </h6>
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Топшириқ мазмуни</th>
                        <th><span style="color: rgb(192, 0, 0);">{{ $g.getDate(new Date()) }}</span> йил <br> ҳолатига бажарилган ишлар</th>
                        <th>Ижро муддати</th>
                        <th style="border-right:none;">Масъул ижрочилар</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ form.title }}</td>
                        <td>
                            <template v-for="(item, index) in getTask.users">
                                <div v-for="(ans, ind) in item.items" v-html="ans.text"></div>
                            </template>
                        </td>
                        <td style="text-align:center;">{{ $g.getDate( form.exp_date) }}</td>
                        <td style="border-right:none;">
                            <p  v-for="(user, index) in selectedUsersList" :class="{ selected: user.svot }">
                                {{ user.name.charAt(0) }}.  {{ user.surename }},
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
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
        comment: {text: null, id: null},
        modalcancel: null,
        selectedStr: null,
    };
  },
  computed: {
    ...mapGetters("task", ["getTaskMassage", 'getTask']),
    ...mapGetters("user", ["getUserList", 'getStructureList']),
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
        val.svot = true
        this.selectedUsersList.push(val);
      }
    },
  },
  updated() {
    feather.replace();
  },
  async mounted() {
    await this.rerenderData();
    await this.ActionStructureList();
    this.isLoading = false
    this.modalcancel = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
    })

  },
  methods: {
    ...mapActions("task", ["actionEditTask", "actionUpdateTask", "actionAcceptTask", "actionRejectTask"]),
    ...mapActions("user", ["ActionUserList", 'ActionStructureList']),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    disabledStartDate(date) {
        return date <  new Date();
    },
    async cancelTask(){
        await this.actionRejectTask(this.comment);
        if(this.getTaskMassage.success){
            this.modalcancel.hide();
            this.comment = {text: null, id: null}
            toast.fire({
                type: "success",
                icon: "success",
                title: this.getTaskMassage.message,
            });
        }
    },
    async acceptTask(){
        await this.actionAcceptTask({id: this.$route.params.taskId});
        if(this.getTaskMassage.success){
            toast.fire({
                type: "success",
                icon: "success",
                title: this.getTaskMassage.message,
            });
        }
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
        this.comment.id = this.$route.params.taskId
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
      document.querySelector("#" + labelId).innerHTML = name ? name : 'Файл юклаш';
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
        if (this.getTaskMassage.success) {
            this.rerenderData()
            this.newItem.text = ''
            this.newItem.file = null
          toast.fire({
            type: "success",
            icon: "success",
            title: "Топшириқ юклатилди!",
          });
          this.requiredInput = false;
        } else {
          toast.fire({
            type: "error",
            icon: "error",
            title: "Илтимос текшириб кўринг",
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
