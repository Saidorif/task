<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				User Tasks
			</h4>
            <!-- <router-link class="btn_green" to="/crm/tasks/add"><i data-feather="plus" class="sidebar_icon"></i> Добавить</router-link> -->
		</div>
        <div class="jv_card">
            <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Название</th>
							<th scope="col">Срок</th>
							<th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody >
						 <tr v-for="(task,index) in getUserTasks">
							<td scope="row">{{index+1}}</td>
							<td scope="row">{{task.task.title}}</td>
							<td scope="row">{{ $g.getDate(task.task.exp_date) }}г</td>
							<td>
                                <router-link tag="button" class="btn_blue_icon" :to='`/crm/user-task/edit/${task.id}`'>
									<i data-feather="eye" class="sidebar_icon" ></i>
								</router-link>
								<button class="btn_red_icon" @click="deleteConts(task.id)">
									<i data-feather="trash" class="sidebar_icon" ></i>
								</button>
							</td>
						</tr>
					</tbody>
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
			await this.actionUserTasks()
            feather.replace()

		},
		computed:{
			...mapGetters('usertask',['getUserTasks','getMassage'])
		},
		methods:{
			...mapActions('usertask',['actionUserTasks']),
			async getResults(page = 1){
				await this.actionUserTasks(page)
			},
		}
	}
</script>
<style scoped>

</style>
