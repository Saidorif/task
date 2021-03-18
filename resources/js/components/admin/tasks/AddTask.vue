<template>
	<div class="add_action">
        <div class="page_header">
			<h4 class="header_title">
				Add Task
			</h4>
            <router-link class="btn_black" to="/crm/tasks"><i data-feather="arrow-left" class="sidebar_icon"></i> Назад</router-link>
		</div>
        <div class="jv_card">
            <form @submit.prevent.enter="saveAction" >
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
                    <div class="table-responsive mt-5">
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
                                <tr v-for="(user, index) in selectedUsersList">
                                    <td>{{ index + 1}}</td>
                                    <td>{{user.name}} {{ user.surename }} {{user.lastname}}</td>
                                    <td>
                                        <button class="btn_blue_icon" @click="svotUser(index)">
                                            <i data-feather="check-square" class="sidebar_icon" ></i>
                                        </button>
                                        <button class="btn_red_icon" @click="deleteUser(index)">
                                            <i data-feather="trash" class="sidebar_icon" ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form_btn_block">
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
	export default{
		components: {
	    	Multiselect,
            DatePicker,
	  	},
		data(){
			return{
				form:{
					title:'',
					items:[{text: '', file: ''}],
                    exp_date: '',
                    users:[],
				},
                userlist: [],
                selectedUsersList: [],
				requiredInput:false,
				isLoading: false,
                selectedUser: null,
			}
		},
		computed:{
			...mapGetters('task',['getMassage']),
			...mapGetters('user', ['getUserList']),
		},
        watch:{
            selectedUser: function(val){
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
            nameWithLang ({ name, surename, lastname}) {
                return `${name} ${surename} ${lastname}`
            },
            deleteUser(ind){
                console.log(ind)
            },
            svotUser(ind){
                console.log(ind)
            },
            asyncFind(val){
                let trval = cril().reverse(val)
                this.userlist = this.getUserList.filter((el)=>{
                    return el.name.toLowerCase().indexOf(trval.toLowerCase()) > -1;
                })
            },
		    async saveAction(){
		    	if (this.form.title != '' && this.form.exp_date != '' && this.selectedUser.length){
                    this.form.users = this.selectedUser.map((item)=>{
                        return {user_id: item.id, svot: item.svot ? 1 : 0}
                    })
					await this.actionAddTask(this.form)
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
