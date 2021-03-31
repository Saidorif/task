<template>
	<div class="add_action">
        <div class="page_header">
			<h4 class="header_title">
				Вилоят қўшиш
			</h4>
            <router-link class="btn_black" to="/crm/region"><i data-feather="arrow-left" class="sidebar_icon"></i> Орқага</router-link>
		</div>
        <div class="jv_card">
            <form @submit.prevent.enter="saveAction" >
					<div class="row align-items-end">
					  <div class="input_style col-md-4">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="contName"
					    	v-model="form.name"
                            required
					    	:class="isRequired(form.name) ? 'isRequired' : ''"
				    	>
					    <label for="contName">Вилоят номи</label>
					  </div>
					  <div class="form_btn_block">
					  	<button type="submit" class="btn_green">
					  		<i class="sidebar_icon" data-feather="save"></i>
						  	Сақлаш
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
				},
				requiredInput:false,
				isLoading: false,
			}
		},
		computed:{
			...mapGetters('region',['getMassage']),
		},
		methods:{
			...mapActions('region',['actionAddRegion']),
			isRequired(input){
	    		return this.requiredInput && input === '';
		    },
		    async saveAction(){
		    	if (this.form.name != ''){
					await this.actionAddRegion(this.form)
					if (this.getMassage.success) {
						toast.fire({
					    	type: 'success',
					    	icon: 'success',
							title: this.getMassage.message,
					    })
						this.$router.push("/crm/region");
						this.requiredInput =false
					}else{
						toast.fire({
					    	type: 'error',
					    	icon: 'error',
							title: 'Такой Region уже существует!',
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
