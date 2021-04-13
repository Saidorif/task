<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Менинг топшириқларим
			</h4>
            <!-- <router-link class="btn_green" to="/crm/tasks/add"><i data-feather="plus" class="sidebar_icon"></i> Добавить</router-link> -->
		</div>
        <div class="jv_card">
            <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col" style="width:40px;">№</th>
							<th scope="col">Қисқа мазмуни</th>
							<th scope="col" style="width:100px;">Муддати</th>
							<th scope="col" style="width:140px">Жорий холати</th>
							<th scope="col" style="width:110px;">Тахрирлаш</th>
						</tr>
					</thead>
					<tbody >
						 <tr v-for="(task,index) in getUserTasks" :class="{ 'unread': task.read == 0 }">
							<td scope="row">{{index+1}}  </td>
							<td scope="row" style="text-align:left;">{{task.task.title}} <span class="reads_count" v-if="task.unreads_count > 0">{{task.unreads_count}}</span></td>
							<td scope="row">{{ $g.getDate(task.task.exp_date) }}</td>
                            <td>
                                <span class="alert alert-danger jv_alert" v-if="task.task.status == 'rejected'">Рад этилган</span>
                                <span class="alert alert-success jv_alert" v-if="task.task.status == 'accepted'">Қабул қилинган</span>
                                <span class="alert alert-warning jv_alert" v-if="task.task.status == 'pending'">Тасдиқланмаган</span>
                                <span class="alert alert-info jv_alert" v-if="task.task.status == 'active'">Бажарилмоқда</span>
                                <span class="alert alert-dark jv_alert" v-if="task.task.status == 'draft'">Режада</span>
                            </td>
							<td>
                                <router-link tag="button" class="btn_blue_icon" :to='`/crm/user-task/edit/${task.id}`' v-tooltip.top-center="'Танишиш'">
									<i data-feather="eye" class="sidebar_icon" ></i>
								</router-link>
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
