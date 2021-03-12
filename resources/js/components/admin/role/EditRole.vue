<template>
	<div class="edit_role">
		<div class="card">
		  	<div class="card-header">
			    <h4 class="title_user">
			    	<i class="peIcon pe-7s-id"></i>
				    Edit Role
				</h4>
				<router-link class="btn btn-primary back_btn" to="/crm/role"><span class="peIcon pe-7s-back"></span> Назад</router-link>
		  	</div>
		  	<div class="card-body">
		  		<form @submit.prevent.enter="saveRole" >
					<div class="row">
					  <div class="form-group col-md-9">
					    <label for="roleName">Role Name</label>
					    <input 
					    	type="text" 
					    	class="form-control input_style" 
					    	id="roleName" 
					    	placeholder="Role Name"
					    	v-model="form.name"
					    	:class="isRequired(form.name) ? 'isRequired' : ''"  
				    	>
					  </div>
					 <div class="form-group col-md-9">
					    <label for="roleName">Label</label>
					    <input 
					    	type="text" 
					    	class="form-control input_style" 
					    	id="roleName" 
					    	placeholder="Label"
					    	v-model="form.label"
					    	:class="isRequired(form.label) ? 'isRequired' : ''"  
				    	>
					  </div>
					  <div class="form-group col-lg-3 form_btn">
					  	<button type="submit" class="btn btn-primary btn_save_category">
					  		<i class="fas fa-save"></i>
						  	Сохранить
						</button>	
				  	  </div>
					</div>
				</form>
		  	</div>
	  	</div>
	</div>
</template>
<script>
	import {mapActions, mapGetters} from 'vuex'
	export default{
		data(){
			return{
				form:{
					name:'',
					label:''
				},
				requiredInput:false
			}
		},
		computed:{
			...mapGetters('role',['getMassage','getRole'])
		},
		async mounted(){
			await this.actionEditRole({id:this.$route.params.roleId})
			this.form = this.getRole
		},
		methods:{
			...mapActions('role',['actionEditRole','actionUpdateRole']),
			isRequired(input){
	    		return this.requiredInput && input === '';
		    },
		    async saveRole(){
		    	if (this.form.name != '' && this.form.name != null){
					await this.actionUpdateRole(this.form)
					this.$router.push("/crm/role");
					this.requiredInput =false
				}else{
					this.requiredInput =true
				}
		    }
		}
	}
</script>
<style scoped>
	
</style>