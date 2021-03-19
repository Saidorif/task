<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Users
			</h4>
		</div>
        <div class="jv_card">
            <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">F.I.O</th>
							<th scope="col">Email</th>
							<th scope="col">Управление</th>
							<th scope="col">Должность</th>
						</tr>
					</thead>
					<tbody v-if="getAllUsers.data && getAllUsers.data.length">
						 <tr v-for="(cont,index) in getAllUsers.data">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.name}} {{cont.surename}} {{cont.lastname}}</td>
							<td>{{cont.email}}</td>
							<td>{{cont.position.structure.name}}</td>
							<td>{{cont.position.name}}</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getAllUsers" @pagination-change-page="getResults"></pagination>
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
            let page = 1;
			await this.actionAllUsers(page)
            feather.replace()

		},
		computed:{
			...mapGetters('users',['getAllUsers'])
		},
		methods:{
			...mapActions('users',['actionAllUsers']),
			async getResults(page = 1){
				await this.actionAllUsers(page)
			},
		}
	}
</script>
<style scoped>

</style>
