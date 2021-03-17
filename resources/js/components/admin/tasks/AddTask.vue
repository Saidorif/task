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
                        <div class="col-md-3">
                            <multiselect
                                v-model="selectedUser"
                                :options="userlist"
                                :custom-label="nameWithLang"
                                placeholder="Выберите User"
                                selectLabel="Нажмите Enter, чтобы выбрать"
                                :multiple="true"
                                deselectLabel="Нажмите Enter, чтобы удалить"
                                :class="isRequired(selectedUser) ? 'isRequired' : ''"
                                :allow-empty="false"
                                @tag="selectSlot"
                                label="name surename"
                                :internal-search="false"
                                track-by="name" @search-change="asyncFind"></multiselect>
                        </div>
                        <div class="col-md-2">
                            <date-picker v-model="form.exp_date" placeholder="Выберите срок" value-type="format" format="DD.MM.YYYY"></date-picker>
                        </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="contName"
					    	v-model="form.title"
                            required
					    	:class="isRequired(form.title) ? 'isRequired' : ''"
				    	>
					    <label for="contName">Action Name</label>
					  </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="contName"
					    	v-model="form.code"
					    	:class="isRequired(form.code) ? 'isRequired' : ''"
                            required
				    	>
					    <label for="contName">Code</label>
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
				requiredInput:false,
				isLoading: false,
                selectedUser: [],
			}
		},
		computed:{
			...mapGetters('task',['getMassage']),
			...mapGetters('user', ['getUserList']),
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
            asyncFind(val){
                let trval = cril().reverse(val)
                this.userlist = this.getUserList.filter((el)=>{
                    return el.name.toLowerCase().indexOf(trval.toLowerCase()) > -1;
                })
            },
		    async saveAction(){
		    	if (this.form.title != '' && this.form.exp_date != '' && this.selectedUser.length){
                    this.form.users = this.selectedUser
					await this.actionAddTask(this.form)
					if (this.getMassage.success) {
						toast.fire({
					    	type: 'success',
					    	icon: 'success',
							title: 'Action добавлен!',
					    })
						this.$router.push("/crm/action");
						this.requiredInput =false
					}else{
						toast.fire({
					    	type: 'error',
					    	icon: 'error',
							title: 'Такой Action уже существует!',
					    })
					}
				}else{
					this.requiredInput =true
				}
		    },
		    filterController(value){
	      		if(value != ''){
					  this.isLoading = true
					setTimeout(()=>{
		          		this.actionContsFind({name: value,})
						this.isLoading = false
					},1000)
				}
		    },
		    selectedItem(selectedOption, id){
	      		this.findController = selectedOption
	      		this.form.conts_id = this.findController.id
			},
            selectSlot(){
                console.log('ss')
            }
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
