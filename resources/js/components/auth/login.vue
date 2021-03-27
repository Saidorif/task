<template>
	<div class="login_card">
        <div class="login_card_left">
            <div class="login_alert" v-if="errorMsg"><i class="fas fa-exclamation-circle mr-2"></i> Неверный логин  или пароль </div>
            <div class="form_content">
                <h2 class="login_title"> Вход в <br> персональный  кабинет </h2>
                <form @submit.enter.prevent="onSubmit">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Email" v-model="form.email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i data-feather="user"></i>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Пароль" v-model="form.password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i data-feather="lock"></i>
                                </div>
                            </div>
                        </div>
                       <button type="submit" class="btn_blue">Войти</button>
                </form>
            </div>
        </div>
        <div class="login_card_right">
            <img src="/img/loginbg.svg" alt="">
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
        updated() {
            feather.replace()
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
    .login_card{
        width: 100%;
        height: 100%;
        overflow: hidden;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: stretch;
    }
    .login_card_left{
        width: 30%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #0c062e;
    }
    .form_content{
        width: 325px;
    }
    .login_card_right{
        width: 70%;
        display: flex;
        justify-content: center;
    }
    .login_card_right img{
        width: 70%;
    }
    .login_card_left .login_title{
        color: #fff;
        margin-bottom: 30px;
    }
    .input-group{
        position: relative;
    }
    .input-group input{
        width: 100%;
        border: 2px solid transparent;
        font-size: 18px;
        padding: 6px 15px;
        background: white;
        border-radius: 6px;
        margin-bottom: 30px;
        overflow: hidden;
    }
    .input-group input:focus{
        outline: none;
    }
    .input-group-append{
        position: absolute;
        right: 0;
        bottom: 0;
        top: 0;
    }
    .btn_blue{
        width: 100%;
        text-align: center;
        justify-content: center;
        font-size: 16px;
    }
</style>
