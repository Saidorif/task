<template>
	<div class="card lognCard">
	    <div class="card-body login-card-body">
			<div class="login_alert" v-if="errorMsg"><i class="fas fa-exclamation-circle mr-2"></i> Неверный логин  или пароль </div>
			<div class="form_content">
				<div class="login_logo">
					<img src="/img/loginLogo.svg" width="100">
				</div>
				<p class="login_title"> Вход в персональный кабинет 11</p>
				<form @submit.enter.prevent="onSubmit">
					<div class="input-group">
						<input type="email" class="form-control" placeholder="Email" v-model="form.email">
						<div class="input-group-append">
							<div class="input-group-text">
								<img src="/img/user.png" alt="">
							</div>
						</div>
					</div>
					<div class="input-group">
						<input type="password" class="form-control" placeholder="Пароль" v-model="form.password">
						<div class="input-group-append">
							<div class="input-group-text">
								<img src="/img/key.png" alt="">
							</div>
						</div>
					</div>
					<div class="row">
						<!-- <div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" id="remember">
								<label for="remember">
									Remember Me
								</label>
							</div>
						</div> -->
						<!-- /.col -->
						<div class="col-12">
							<button type="submit" class="btn btn-primary btn-block">Войти</button>
						</div>
						<!-- /.col -->
					</div>
				</form>
			</div>
	    </div>
  	</div>
</template>
<script>
	import { mapActions, mapGetters } from "vuex";
	import { TokenService } from "./../../services/storage.service";
	export default{
		data() {
		    return {
		      form: {
		        email: "",
		        password: ""
			  },
			  errorMsg: null
		    };
	  	},
	 	computed: {
		    ...mapGetters(["authenticationErrorCode","authenticationError"])
	  	},
	  	async mounted(){

	  	},
	  	methods: {
		    ...mapActions(["login"]),
		    async onSubmit(){
		      this.$Progress.start();
		      if (this.form.email != "" && this.form.password != "") {
		        await this.login(this.form);
		        await this.authenticationErrorCode;
		        if (!this.authenticationErrorCode){
		          toast.fire({
					type: "success",
					icon: 'success',
		            title: "Вошли в систему!"
		          });
		          this.$Progress.finish();
		          // setTimeout(()=>{
		          // 	window.location = '/crm/dashboard';
		          // },100)
				//   this.$router.push("/crm/dashboard");
				  window.location.href = "/crm/dashboard";
		        }else{
					this.errorMsg = this.authenticationError
				  	this.$Progress.fail();

		        }
		      }
		    }
	  	},
	}
</script>
<style scoped>

</style>
