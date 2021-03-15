<template>
	<div class="action">
		<div class="card">
		  	<div class="card-header">
				<div class="header_title">
				    <h4 class="title_user">
				    	<i  class="sidebar_icon fas fa-star"></i>
					     Action 
					</h4>
					<div class="add_user_btn">
						<router-link class="btn btn-primary" to="/crm/action/add"><i class="fas fa-plus"></i> Добавить</router-link>
		            </div>
		  		</div>
		  	</div>
		  	<div class="card-body">
			  <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Название</th>
							<th scope="col">Controller</th>
							<th scope="col">Code</th>
							<th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cont,index) in getActions.data">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.name}}</td>
							<td>controller</td>
							<td>{{cont.code}}</td>
							<td>
								<router-link tag="button" class="btn_transparent" :to='`/crm/action/edit/${cont.id}`'>
									<i class="fas fa-pen"></i>
								</router-link>
							<!-- 	<button class="btn_transparent" @click="deleteConts(cont.id)">
									<i class="fas fa-trash"></i>
								</button> -->
							</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getActions" @pagination-change-page="getResults"></pagination>
				</table>
			  </div>
		  </div>
	  	</div>
	</div>
</template>
<script>
	import {mapActions,mapGetters} from 'vuex'
	export default{
		data(){
			return{

			}
		},
		async mounted(){
			await this.actionActions()
		},
		computed:{
			...mapGetters('action',['getActions','getMassage'])
		},
		methods:{
			...mapActions('action',['actionActions']),
			async getResults(page = 1){ 
				await this.actionActions(page)
			},
		}
	}
</script>
<style scoped>
	
</style>
