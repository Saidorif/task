<template>
	<div class="add_action">
        <div class="page_header">
			<h4 class="header_title">
				Add Area
			</h4>
            <router-link class="btn_black" to="/crm/area"><i data-feather="arrow-left" class="sidebar_icon"></i> Назад</router-link>
		</div>
        <div class="jv_card">
            <form @submit.prevent.enter="saveAction" >
					<div class="row align-items-end">
                        <div class="col-md-4">
                            <multiselect v-model="form.region_id" :options="regions" :searchable="false" :close-on-select="false" :show-labels="false" placeholder="Pick a value"></multiselect>
                        </div>
					  <div class="input_style col-md-4">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="contName"
					    	v-model="form.name"
                            required
					    	:class="isRequired(form.name) ? 'isRequired' : ''"
				    	>
					    <label for="contName">Area Name</label>
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
					name:'',
					region_id:'',
				},
				requiredInput:false,
				isLoading: false,
			}
		},
		computed:{
			...mapGetters('area',['getMassage']),
		},
		methods:{
			...mapActions('area',['actionAddArea']),
			isRequired(input){
	    		return this.requiredInput && input === '';
		    },
		    async saveAction(){
		    	if (this.form.name != ''){
					await this.actionAddArea(this.form)
					if (this.getMassage.success) {
						toast.fire({
					    	type: 'success',
					    	icon: 'success',
							title: this.getMassage.message,
					    })
						this.$router.push("/crm/area");
						this.requiredInput =false
					}else{
						toast.fire({
					    	type: 'error',
					    	icon: 'error',
							title: 'Такой Area уже существует!',
					    })
					}
				}else{
					this.requiredInput =true
				}
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
