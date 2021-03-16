<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Tasks
			</h4>
            <router-link class="btn_green" to="/crm/tasks/add"><i data-feather="plus" class="sidebar_icon"></i> Добавить</router-link>
		</div>
        <div class="jv_card">
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
					<!--<tbody>
						 <tr v-for="(cont,index) in getActions.data">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.name}}</td>
							<td>controller</td>
							<td>{{cont.code}}</td>
							<td>
								<router-link tag="button" class="btn_transparent" :to='`/crm/action/edit/${cont.id}`'>
									<i class="fas fa-pen"></i>
								</router-link>
								<button class="btn_transparent" @click="deleteConts(cont.id)">
									<i class="fas fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getActions" @pagination-change-page="getResults"></pagination> -->
				</table>
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
                       feather.replace()
			// await this.actionActions()
		},
		computed:{
			// ...mapGetters('action',['getActions','getMassage'])
		},
		methods:{
			// ...mapActions('action',['actionActions']),
			// async getResults(page = 1){
			// 	await this.actionActions(page)
			// },
		}
	}
</script>
<style scoped>

</style>
