<template>
	<div class="edit_cont">
		<div class="card">
		  	<div class="card-header">
			    <h4 class="title_user">
			    	<i class="peIcon pe-7s-id"></i>
				    Edit Controller
				</h4>
				<router-link class="btn btn-primary back_btn" to="/crm/conts"><span class="peIcon pe-7s-back"></span> Назад</router-link>
		  	</div>
		  	<div class="card-body">
		  		<form @submit.prevent.enter="saveCont" >
					<div class="row">
					  <div class="form-group col-md-9">
					    <label for="contName">Controller Name</label>
					    <input 
					    	type="text" 
					    	class="form-control input_style" 
					    	id="contName" 
					    	placeholder="Controller Name"
					    	v-model="form.name"
					    	:class="isRequired(form.name) ? 'isRequired' : ''"  
				    	>
					  </div>
					  <div class="form-group col-md-9">
					    <label for="contName">Label</label>
					    <input 
					    	type="text" 
					    	class="form-control input_style" 
					    	id="contName" 
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
			...mapGetters('conts',['getMassage','getCont'])
		},
		async mounted(){
			await this.actionEditCont(this.$route.params.contId)
			this.form = this.getCont
		},
		methods:{
			...mapActions('conts',['actionUpdateCont','actionEditCont']),
			isRequired(input){
	    		return this.requiredInput && input === '';
		    },
		    async saveCont(){
		    	if (this.form.name != '' && this.form.label != ''){
					await this.actionUpdateCont(this.form)
					this.$router.push("/crm/conts");
					this.requiredInput =false
					toast.fire({
				    	type: 'success',
				    	icon: 'success',
						title: 'Controller изменен!',
				    })
				}else{
					this.requiredInput =true
				}
		    }
		}
	}
</script>
<style scoped>
	
</style>