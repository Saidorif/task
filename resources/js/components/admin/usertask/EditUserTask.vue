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
          class="alert alert-success jv_alert"
          v-if="form.status == 'active'"
        >
          Активный
        </div>
        <div
          class="alert alert-danger jv_alert"
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
                            <th>Fayl</th>
                            <th>Holati</th>
                            <th>Taxrirlash</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr  v-for="(ans, ind) in item.items">
                                <td>{{ $g.getDate(ans.created_at) }}</td>
                                <td style="text-align: initial;width:80%;"> <div v-html="ans.text"></div> </td>
                                <td style="width:10%;">
                                    <a :href="'/'+ans.file" v-if="ans.file" class="btn_blue_icon" download="">
                                        <i class="sidebar_icon" data-feather="download"></i>
                                    </a>
                                </td>
                                <td style="width:10%;">
                                    <div class="btn_group" v-if="userId == svotId && userId != item.user_id && ans.status == 'pending'" >
                                        <button class="btn_green_icon" title="accept" @click="acceptTask(ans)">
                                            <i data-feather="check" class="sidebar_icon" ></i>
                                        </button>
                                        <button class="btn_red_icon" title="cancel" @click="openDenyModal(ans)">
                                            <i data-feather="slash" class="sidebar_icon" ></i>
                                        </button>
                                    </div>
                                    <div v-else>
                                        <span class="alert alert-danger jv_alert" v-if="ans.status == 'rejected'">Rad etilgan</span>
                                        <span class="alert alert-success jv_alert" v-if="ans.status == 'accepted'">Qabul qilingan</span>
                                        <span class="alert alert-warning jv_alert" v-if="ans.status == 'pending'">Tekshirilmoqda</span>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn_blue_icon" title="update" v-if="ans.status == 'rejected' &&  userId == item.user_id" @click="updateAnswer(ans)">
                                         <i data-feather="edit-2" class="sidebar_icon" ></i>
                                    </button>
                                    <button class="btn_red_icon" title="show" v-if="ans.status == 'rejected' &&  userId == item.user_id" @click="showComment(ans)">
                                         <i data-feather="monitor" class="sidebar_icon" ></i>
                                    </button>
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
                    >File Upload</label>
                    <input
                        type="file"
                        id="file"
                        @change="inputFileUpload($event, 'inputFileLabel', answer)"
                    />
                </div>
            </div>
        </div>
        <div class="form_btn_block">
            <button type="submit" class="btn_blue mr_15">
              <i class="sidebar_icon" data-feather="save"></i>
              Сохранить
            </button>
            <button type="button" @click="fineshTask" class="btn_green">
              <i class="sidebar_icon" data-feather="send"></i>
              Закончить
            </button>
        </div>
    </form>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form class="modal-dialog"   @submit.prevent.enter="cancelTask" >
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rad etilganlik sababi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input_style">
                    <textarea name="" v-model="comment.text" id="comment" class="input_style"  cols="30" rows="10" required></textarea>
                     <label for="comment">Sabab</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
                <button type="submit" class="btn btn-primary">Rad etish</button>
            </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="rejectCommentModal" tabindex="-1" aria-labelledby="rejectCommentModalLabel" aria-hidden="true">
        <form class="modal-dialog" style="    max-width: 80%;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectCommentModalLabel">Rad etilganlik sababi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input_style">
                        <textarea name="" v-model="comment.text" id="comment" class="input_style"  cols="30" rows="10" required disabled></textarea>
                        <label for="comment">Sabab</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Yopish</button>
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
      svotId: null,
      userId: null,
      comment: {text: null, id: null},
      modalcancel: null,
    };
  },
  computed: {
    ...mapGetters("task", ["getTask"]),
    ...mapGetters("usertask", ["getMassage", 'getUserTask']),
  },
  updated() {
    feather.replace();
  },
  async mounted() {
    await this.dataRender();
    this.userId = JSON.parse(localStorage.getItem('user')) ? JSON.parse(localStorage.getItem('user')).id : null;
    this.modalcancel = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
    })
    feather.replace();
  },
  methods: {
    ...mapActions("task", ["actionEditTask", "actionUpdateTask"]),
    ...mapActions("usertask", [
        "actionSendAnswer",
        "actionEditUserTask",
        'actionAcceptTaskSvot',
        'actionRejectTaskSvot',
        'actionApproveTaskSvot',
        'actionUpdateUserTask']
        ),
    isRequired(input) {
      return this.requiredInput && input === "";
    },
    async fineshTask(){

    },
    async dataRender(){
        let data = {
            id: this.$route.params.taskId,
        };
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
                this.svotId = item.user_id
            }
            return data;
        });
        this.allItems = this.getUserTask.task.items;
    },
    async acceptTask(ans){
        await this.actionAcceptTaskSvot({id: ans.id})
        await this.dataRender()
        if(this.getMassage.success){
            toast.fire({
                type: "success",
                icon: "success",
                title: this.getMassage.message,
            });
        }
    },
    openDenyModal(ans){
        this.modalcancel.show()
        this.comment.id = ans.id
    },
    async cancelTask(){
        if(this.comment.text != ''){
            await this.actionRejectTaskSvot(this.comment);
            await this.dataRender()
            this.modalcancel.hide()
            this.comment = {text: ''}
            if(this.getMassage.success){
                toast.fire({
                    type: "success",
                    icon: "success",
                    title: this.getMassage.message,
                });
            }
        }

    },
    showComment(anw){
        let myModal = new bootstrap.Modal(document.getElementById('rejectCommentModal'), {
            keyboard: false
        })
        myModal.show()
        this.comment = anw.comments[anw.comments.length - 1]
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
    updateAnswer(anw){
        this.answer = anw
    },

    async saveAction() {
      if (
        this.answer.text != ""
      ) {

        let formData = new FormData();
        formData.append("text", this.answer.text);
        if(this.answer.file != null){
            formData.append("file", this.answer.file);
        }
        formData.append("task_id", this.allItems[0].task_id);
        formData.append("parent_id", this.$route.params.taskId);
        if(this.answer.id){
            formData.append("id", this.answer.id);
            await this.actionApproveTaskSvot(this.answer)
            await this.actionUpdateUserTask({id:  this.answer.id, data: formData});
        }else{
            await this.actionSendAnswer(formData);
        }
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
