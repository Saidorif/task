<template>
  <div class="edit_action">
    <div class="page_header">
      <h4 class="header_title">Edit Task</h4>
      <router-link class="btn_black" to="/crm/user-task"
        ><i data-feather="arrow-left" class="sidebar_icon"></i>
        Назад</router-link
      >
    </div>
    <div class="jv_card jv_card_header">
      <div class="input_style col-md-10">
        <h5>Topshiriq mavzusi</h5>
        <p>{{ form.title }}</p>
      </div>
      <div class="col-md-1">
        <h6>Срок</h6>
        <p>{{ form.exp_date }}</p>
      </div>
      <div class="col-md-1">
        <h6>Status</h6>
        <div
          class="alert alert-success"
          style="padding: 0.5rem 0.8rem; display: inline-flex"
          v-if="form.status == 'active'"
        >
          Активный
        </div>
        <div
          class="alert alert-danger"
          style="padding: 0.5rem 0.8rem; display: inline-flex"
          v-else
        >
          Неактивный
        </div>
      </div>
    </div>
    <h2>Ma'sul xodimlar</h2>
    <div class="jv_card">
      <div class="table-responsive" v-if="selectedUsersList.length">
        <table
          class="table table-bordered text-center table-hover table-striped"
        >
          <thead>
            <tr>
              <th>№</th>
              <th>Ф.И.О</th>
              <th>Управление</th>
              <th>Должность</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(user, index) in selectedUsersList"
              :class="{ selected: user.svot }"
            >
              <td>{{ index + 1 }}</td>
              <td>{{ user.name }} {{ user.surename }} {{ user.lastname }}</td>
              <td>{{ user.position.structure.name }}</td>
              <td>{{ user.position.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <h2>Topshiriq mazmuni</h2>
    <div class="jv_card" v-for="(item, index) in allItems">
      <div class="col-md-12" v-html="item.text"></div>
        <a :href="'/'+item.file" class="btn_black btn_download" download="">
            <i class="sidebar_icon" data-feather="download"></i>Download</a>
    </div>
    <h2>Topshiriq bo'yicha bajarilgan ishlar</h2>
    <div class="jv_card" v-if="getUserTask.task" >
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation" v-for="(item, index) in getUserTask.task.users">
                <button class="nav-link" :class="getUserTask.user_id == item.user_id ? 'active' : ''"
                    :id="'home-tab'+index"
                    data-bs-toggle="tab"
                    :data-bs-target="'#home'+index"
                    type="button" role="tab"
                    :aria-controls="'home'+index" :aria-selected="true">{{item.user.name}} {{item.user.surename}}</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade"  :class="getUserTask.user_id == item.user_id ? 'show active' : ''"
            :id="'home'+index" role="tabpanel" :aria-labelledby="'home-tab'+index" v-for="(item, index) in getUserTask.task.users">
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
    <h2>Xisobod</h2>
    <form class="jv_card"  @submit.prevent.enter="saveAction" enctype="multipart/form-data">
        <div class="jv_card_body">
            <div class="col-md-9 mr_15">
            <label for="text" class="title_label">Xisobot matni</label>
            <vue-editor id="text" v-model="answer.text" />
            </div>
            <div class="col-md-3">
            <div class="input_style_file" >
                <label for="file" id="inputFileLabel"
                >File Upload</label
                >
                <input
                type="file"
                id="file"
                @change="
                    inputFileUpload($event, 'inputFileLabel', answer)
                "
                />
            </div>
            </div>
        </div>
        <div class="form_btn_block">
            <button type="submit" class="btn_blue">
              <i class="sidebar_icon" data-feather="send"></i>
              Отправить
            </button>
        </div>
    </form>
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
        status: "",
      },
      answer:{
          text: '',
          file: null,
      },
      allItems: [{ text: "", file: null }],
      userlist: [],
      selectedUsersList: [],
      requiredInput: false,
      isLoading: false,
      selectedUser: null,
      hasSvot: false,
    };
  },
  computed: {
    ...mapGetters("task", ["getTask"]),
    ...mapGetters("usertask", ["getMassage", 'getUserTask']),
    ...mapGetters("user", ["getUserList"]),
  },
  updated() {
    feather.replace();
  },
  async mounted() {
    let data = {
      id: this.$route.params.taskId,
    };
    await this.ActionUserList();
    // await this.actionEditTask(data);
    await this.actionEditUserTask(data);
    this.userlist = this.getUserList;
    this.form.title = this.getUserTask.task.title;
    this.form.status = this.getUserTask.task.status;
    this.form.exp_date = this.$g.getDate(this.getUserTask.task.exp_date);
    this.selectedUsersList = this.getUserTask.task.users.map((item) => {
      let data = item.user;
      data.svot = item.svot;
      if (item.svot == 1) {
        this.hasSvot = true;
      }
      return data;
    });
    this.allItems = this.getUserTask.task.items;
    feather.replace();
  },
  methods: {
    ...mapActions("task", ["actionEditTask", "actionUpdateTask"]),
    ...mapActions("usertask", ["actionSendAnswer", "actionEditUserTask"]),
    ...mapActions("user", ["ActionUserList"]),
    isRequired(input) {
      return this.requiredInput && input === "";
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
    async saveAction() {
      if (
        this.answer.title != ""
      ) {
        let formData = new FormData();
        formData.append("text", this.answer.text);
        if(this.answer.file != null){
            formData.append("file", this.answer.file);
        }
        formData.append("task_id", this.allItems[0].task_id);
        formData.append("parent_id", this.$route.params.taskId);
        await this.actionSendAnswer(formData);
        if (this.getMassage.success) {
            await this.actionEditUserTask({id: this.$route.params.taskId});
            this.answer.text = ''
            this.answer.file = ''
            document.querySelector("#inputFileLabel").innerHTML = '';
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
      } else {
        this.requiredInput = true;
      }
    },
  },
};
</script>
<style scoped>
.jv_card_header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-direction: row;
}
.btn_download{
    position: absolute;
    top: 15px;
    right: 15px;
}
.jv_card_body{
    display: flex;
    justify-content: space-between;
      flex-direction: row;
      align-items: flex-end;
}
</style>
