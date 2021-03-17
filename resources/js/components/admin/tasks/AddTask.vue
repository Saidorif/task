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
                        <div class="col-md-4">
                            <multiselect v-model="form.users" :options="options" :searchable="false" :close-on-select="false" :show-labels="false" placeholder="Pick a value"></multiselect>
                        </div>
					  <div class="input_style col-md-4">
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
					  <div class="input_style col-md-4">
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
	export default{
		components: {
	    	Multiselect,
	  	},
		data(){
			return{
				form:{
					title:'',
					items:[{text: '', file: ''}],
                    exp_date: '',
                    users:[]
				},
				requiredInput:false,
				isLoading: false,
				findController: {},
                options: ['Select option', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched']
			}
		},
		computed:{
			...mapGetters('task',['getMassage']),
		},
		methods:{
			...mapActions('task',['actionAddTask']),
			isRequired(input){
	    		return this.requiredInput && input === '';
		    },
		    async saveAction(){
		    	if (this.form.name != '' && this.form.code != ''){
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
		},
		async mounted(){
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
