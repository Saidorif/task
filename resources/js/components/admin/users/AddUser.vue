<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Фойдаланувчилар
			</h4>
            <router-link class="btn_black" to="/crm/users"><i data-feather="arrow-left" class="sidebar_icon"></i> Орқага</router-link>
		</div>
        <div class="jv_card">
            <form @submit.prevent.enter="saveAction" >
					<div class="row align-items-end">
					  <div class="input_style col-md-3">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="uname"
					    	v-model="form.name"
                            required
					    	:class="isRequired(form.name) ? 'isRequired' : ''"
				    	>
					    <label for="uname">Исм</label>
					  </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="surename"
					    	v-model="form.surename"
                            required
					    	:class="isRequired(form.surename) ? 'isRequired' : ''"
				    	>
					    <label for="surename">Фамилия</label>
					  </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="email"
					    	class="form-control input_style"
					    	id="email"
					    	v-model="form.email"
                            required
					    	:class="isRequired(form.email) ? 'isRequired' : ''"
				    	>
					    <label for="email">Электрон почта</label>
					  </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="password"
					    	class="form-control input_style"
					    	id="password"
					    	v-model="form.password"
                            required
					    	:class="isRequired(form.password) ? 'isRequired' : ''"
				    	>
					    <label for="password">Пароль</label>
					  </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="password"
					    	class="form-control input_style"
					    	id="confirm_password"
					    	v-model="form.confirm_password"
                            required
					    	:class="isRequired(form.confirm_password) ? 'isRequired' : ''"
				    	>
					    <label for="confirm_password">Парольни тасдиқлаш</label>
					  </div>
					  <div class="input_style col-md-3">
                        <select  id="gender" v-model="form.gender" class="form-control input_style" :class="isRequired(form.gender) ? 'isRequired' : ''">
                            <option  value="man">Эркак</option>
                            <option  value="woman">Aёл</option>
                        </select>
					    <label for="gender">Жинс</label>
					  </div>
					  <div class="input_style col-md-3">
					    <input
					    	type="text"
					    	class="form-control input_style"
					    	id="role_id"
					    	v-model="form.role_id"
                            required
					    	:class="isRequired(form.role_id) ? 'isRequired' : ''"
				    	>
					    <label for="role_id">Рол</label>
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
	import {mapActions,mapGetters} from 'vuex'
	export default{
		data(){
			return{
                form:{
                    name: '',
                    surename: '',
                    email: '',
                    password: '',
                    confirm_password: '',
                    gender: 'man',
                    role_id: '',
                    status: '',
                }
			}
		},
		async mounted(){
			await this.actionRoleList()
            feather.replace()

		},
		computed:{
			...mapGetters('users',['getMessage']),
            ...mapGetters('role',['getRoleList'])
		},
		methods:{
			...mapActions('users',['ActionAddUser']),
            ...mapActions('role',['actionRoleList']),
            isRequired(input){
	    		return this.requiredInput && input === '';
		    },
            async saveAction(){
		    	if (this.form.name != ''){
					await this.ActionAddUser(this.form)
					if (this.getMassage.success) {
						toast.fire({
					    	type: 'success',
					    	icon: 'success',
							title: this.getMassage.message,
					    })
						this.$router.push("/crm/users");
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
		}
	}
</script>
<style scoped>
    .input_style{
        margin-bottom: 15px;
    }
</style>
