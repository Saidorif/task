<template>
	<div class="permissions">
		<div class="card">
		  	<div class="card-header">
			    <h4 class="title_user">Разрешения для <span class="roleName">{{rolName}}</span></h4>
			    <router-link class="btn btn-primary back_btn" to="/crm/role">
			    	<span class="peIcon pe-7s-back"></span>
				    Назад
				</router-link>
		  	</div>
		  	<div class="card-body">
	  			<form>
	  				<template v-for="(item,index) in getAllContsActions">
			  			<div class="contActionFrame" v-for="(val,key) in item" v-if="val.length > 0">
			  				<h4 class="heading"><span>{{key}}</span></h4>
					  		<div class="row">
							  	<div class="col-lg-2 col-md-2 form-group form-check"  v-for="(action,k) in val">
							  		<div>
									    <input type="checkbox" v-model="permissions" :value="{action_id:action.id,conts_id:action.conts_id}"   :id="`check${key}${action.code}`" class="form-check-input" checked="true">
									    <label class="form-check-label" :for="`check${key}${action.code}`">
									    	<b>{{action.code}}</b>
									    </label>
							  		</div>
							  		<span class="action_comment">
							  			{{action.name}}
							  		</span>
							  	</div>
					  		</div>
			  			</div>
	  				</template>
		  			<div class="row">
		  				<div class="col-lg-12 col-md-12 btnPermit">
					  		<button type="submit" class="btn btn-outline-primary btnPermit" @click.prevent="save">
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
	import {mapActions, mapGetters, mapState} from 'vuex'
	export default{
		components: {
		},
		data(){
			return{
				permissions: [],
				rolName:null,
			}
		},
		watch:{
			getPermissions:{
				handler(){
					let newArr = this.getPermissions.permissions.map((item)=>{
						return{
							conts_id:item.conts_id,
							action_id:parseInt(item.action_id)
						}
					})
					this.permissions = newArr
					this.rolName = this.getPermissions.role.label
				}
			}
		},
		computed:{
			...mapGetters('permission',[
				'getPermissions','getMassage',
			]),
			...mapGetters('conts',[
				'getAllContsActions',
			]),
		},
		methods:{
			...mapActions('permission',[
				'actionPermission','actionPermissionStore',
			]),
			...mapActions('conts',[
				'actionAllContsActions',
			]),
			async save(){
				if (this.permissions.length > 0) {
					this.permissions.forEach(elem => {
						elem.role_id =  parseInt(this.$route.params.roleId)
					})
                    console.log('ss')
					await this.actionPermissionStore({permissions:this.permissions,id:this.$route.params.roleId})
					await this.actionPermission({role_id:this.$route.params.roleId})
					toast.fire({
				    	type: 'success',
						title: 'Сохранено!',
				    })
				}else{
					swal.fire({
					  type: 'error',
					  title: 'Ошибка',
					  text: 'Выберите action!',
					})
				}
			},

		},
		async mounted(){
			await this.actionPermission({role_id:this.$route.params.roleId})
			await this.actionAllContsActions()
		}
	}
</script>
<style scoped>
	.action_comment,.roleName{
	    text-decoration: underline;
    	text-transform: capitalize;
    	margin-left:10px;
	}
	.heading{
	  position:relative;
	  text-align: center;
      margin-bottom: 15px;
	}
	.heading:before {
	  content: "";
	  display: block;
	  border-top:1px solid #bebebe;
	  width: 100%;
	  height: 2px;
	  position: absolute;
	  top: 50%;
	  z-index: 0;
	}
	.heading span {
	  background: #fff;
	  padding: 0 10px;
	  position: relative;
	  z-index: 1;
	}
	.contActionFrame{
		border: 1px solid #bebebebe;
    	padding: 5px 20px;
	}
	.btnPermit{
	    display: flex;
	    margin-top: 10px;
	    justify-content: flex-end;
	}
</style>
