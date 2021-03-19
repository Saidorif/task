<template>
	<div class="add_action">
        <div class="page_header">
			<h4 class="header_title">
				Add Task
			</h4>
            <router-link class="btn_black" to="/crm/tasks"><i data-feather="arrow-left" class="sidebar_icon"></i> Назад</router-link>
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
                                track-by="name" @search-change="asyncFind">
                                    <!-- <template slot="tag" slot-scope="{ option, remove }">
                                        <span class="multiselect__tag" :class="option.svot ? 'selected' : ''">
                                            <span @click="selectSvot(option)">{{ option.name }} {{ option.surename }}</span>
                                            <i aria-hidden="true" tabindex="1" class="multiselect__tag-icon" @click="remove(option)"></i>
                                        </span>
                                    </template> -->
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
                        >
                        <label for="contName">Title</label>
                    </div>
                    <div class="col-md-2">
                        <date-picker v-model="form.exp_date" placeholder="Выберите срок" value-type="format" format="DD.MM.YYYY"></date-picker>
                    </div>
                    <div class="table-responsive mt-5" v-if="selectedUsersList.length">
                        <table class="table table-bordered text-center table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Ф.И.О</th>
                                    <th>Отдель</th>
                                    <th>Должность</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in selectedUsersList" :class="{ 'selected': user.svot }" >
                                    <td>{{ index + 1}} </td>
                                    <td>{{user.name}} {{ user.surename }} {{user.lastname}}</td>
                                    <td>otdel  </td>
                                    <td>{{user.position.name}}</td>
                                    <td>
                                        <button type="button" class="btn_blue_icon" @click="svotUser(user, index)" v-if="!hasSvot || user.svot">
                                            <i data-feather="check-square" class="sidebar_icon"  ></i>
                                        </button>
                                        <button type="button" class="btn_red_icon" @click="deleteUser(index)">
                                            <i data-feather="trash" class="sidebar_icon" ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <template v-for="(item, index) in allItems">
                        <div class="col-md-9 mt-5">
                            <label for="text" class="title_label">Text</label>
                            <vue-editor id="text" v-model="item.text" />
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn_red_icon" v-if="index > 0" @click="deleteItem(index)" style="margin-bottom: 70px;">
                                <i data-feather="trash" class="sidebar_icon" ></i>
                            </button>
                            <div class="input_style_file">
                                <label for="file" :id="'inputFileLabel'+index">File Upload</label>
                                <input type="file" id="file" @change="inputFileUpload($event, 'inputFileLabel'+index, item)">
                            </div>
                        </div>
                    </template>
                    <div class="form_btn_block">
                        <button type="button" class="btn_blue mr_15" @click.prevent="addItem">
                            <i class="sidebar_icon" data-feather="plus"></i>
                            Add
                        </button>
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

	import Multiselect from 'vue-multiselect';
	import {mapActions,mapGetters} from 'vuex'
    import DatePicker from 'vue2-datepicker';
    import { VueEditor } from "vue2-editor";
	export default{
		components: {
	    	Multiselect,
            DatePicker,
            VueEditor,
	  	},
		data(){
			return{
				form:{
					title:'',
					items:[],
                    exp_date: '',
                    users:[],
				},
                allItems:[{text: '', file: ''}],
                userlist: [],
                selectedUsersList: [],
				requiredInput:false,
				isLoading: false,
                selectedUser: null,
                hasSvot: false,
			}
		},
		computed:{
			...mapGetters('task',['getMassage']),
			...mapGetters('user', ['getUserList']),
		},
        watch:{
            selectedUser: function(val){
                val.svot = false
                if(this.selectedUsersList.length){
                    let found = false
                    this.selectedUsersList.forEach((item)=>{
                        if(item.id == val.id){
                            found = true
                        }
                    })
                    if(!found){
                        this.selectedUsersList.push(val)
                    }
                }else{
                    this.selectedUsersList.push(val)
                }
            }
        },
        updated() {
            feather.replace()
        },
		methods:{
			...mapActions('task',['actionAddTask']),
			...mapActions('user',['ActionUserList']),
			isRequired(input){
	    		return this.requiredInput && input === '';
		    },
            addItem(){
                let item = {text:'',file:''}
                this.allItems.push(item)
            },
            deleteItem(index){
                this.allItems.splice(index, 1)
            },
            nameWithLang ({ name, surename, lastname}) {
                return `${name} ${surename} ${lastname}`
            },
            deleteUser(ind){
                swal.fire({
                    type: 'confirm',
                    toast: false,
                    icon: 'question',
                    title: 'Вы действительно хотите удалить?',
                    confirmButtonText: `Удалить`,
                    cancelButtonText: `Отмена`,
                    showCancelButton: true
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        this.selectedUsersList.splice(ind, 1)
                    }
                })
            },
            inputFileUpload(event, labelId, item){
                if (!event || !event.target || !event.target.files || event.target.files.length === 0) {
                    return;
                }
                const name = event.target.files[0].name;
                document.querySelector('#'+labelId).innerHTML = name;
                item.file = event.target.files[0]
                console.log(item.file)
            },
            svotUser(user, index){
                user.svot = !user.svot
                this.hasSvot = user.svot
                Vue.set(this.selectedUsersList, index, user );
            },
            asyncFind(val){
                let trval = cril().reverse(val)
                this.userlist = this.getUserList.filter((el)=>{
                    return el.name.toLowerCase().indexOf(trval.toLowerCase()) > -1;
                })
            },
		    async saveAction(){
		    	if (this.form.title != '' && this.form.exp_date != '' && this.selectedUsersList.length){
                    this.form.users = this.selectedUsersList.map((item)=>{
                        return {user_id: item.id, svot: item.svot ? 1 : 0}
                    })
                    let formData = new FormData();
                    formData.append("title", this.form.title);
                    formData.append("exp_date", this.form.exp_date);
                    this.form.users.forEach((item, index)=>{
                        formData.append(`users[${index}][user_id]`, item.user_id)
                        formData.append(`users[${index}][svot]`, item.svot)
                    })
                    this.allItems.forEach((item, index)=>{
                        formData.append(`items[${index}][text]`, item.text)
                        formData.append(`items[${index}][file]`, item.file)
                    })
					await this.actionAddTask(formData)
					if (this.getMassage.success) {
						toast.fire({
					    	type: 'success',
					    	icon: 'success',
							title: 'Task добавлен!',
					    })
						this.$router.push("/crm/tasks");
						this.requiredInput =false
					}else{
						toast.fire({
					    	type: 'error',
					    	icon: 'error',
							title: 'Такой Task уже существует!',
					    })
					}
				}else{
					this.requiredInput =true
				}
		    },
		},
		async mounted(){
            await this.ActionUserList()
            this.userlist = this.getUserList
            feather.replace()
		}
	}
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
