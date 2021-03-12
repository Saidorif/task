<template>
	<div class="role">
		<div class="card">
		  	<div class="card-header">
				  <div class="header_title">
			    <h4 class="title_user">
			    	<i  class="peIcon pe-7s-id"></i>
				    Role 
				</h4>
				<router-link class="btn btn-primary" to="/crm/role/add"><i class="fas fa-plus"></i> Add</router-link>
				  </div>

		  	</div>
		  	<div class="card-body">
			  <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Название</th>
							<th scope="col">Label</th>
							<th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(role,index) in getRoles.data">
							<td scope="row">
								{{index+1}}
							</td>
							<td>
								<router-link tag="a" :to='`/crm/role/${role.id}`'>
									{{role.name}}
								</router-link>
							</td>
							<td>{{role.label}}</td>
							<td>
								<router-link tag="button" class="btn_transparent" :to='`/crm/role/edit/${role.id}`'>
									<i class="fas fa-pen"></i>
								</router-link>
								<button class="btn_transparent" @click="deleteRole(role.id)">
									<i class="fas fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getRoles" @pagination-change-page="getResults"></pagination>
				</table>
			  </div>
		  </div>
	  	</div>
	</div>
</template>
<script>
	import {mapActions, mapGetters} from 'vuex'
	export default{
		data(){
			return{

			}
		},
		async mounted(){
			await this.actionRoles()
		},
		computed:{
			...mapGetters('role',['getRoles','getMassage'])
		},
		methods:{
			...mapActions('role',['actionRoles','actionDeleteRole']),
			async getResults(page = 1){ 
				await this.actionRoles(page)
			},
			async deleteRole(id){
				if(confirm("Вы действительно хотите удалить?")){
					let page = 1
					await this.actionDeleteRole(id)
					await this.actionRoles(page)
					toast.fire({
				    	type: 'success',
				    	icon: 'success',
						title: 'Рол удалено!',
				    })
				}
			}
		}
	}
</script>
<style scoped>
	
</style>