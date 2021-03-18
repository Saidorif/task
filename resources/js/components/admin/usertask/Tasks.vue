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
							<th scope="col">Users</th>
							<th scope="col">Срок</th>
							<th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody v-if="getTasks.data && getTasks.data.length">
						 <tr v-for="(cont,index) in getTasks.data">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.title}}</td>
							<td style="padding:0px;">
                                <ul>
                                    <li v-for="(item) in cont.users"  :class="item.svot == 1 ? 'active' : ''">
                                        {{item.user.name}}
                                        {{item.user.surename}}
                                    </li>
                                </ul>
                            </td>
							<td>{{$g.getDate(cont.exp_date)}}г</td>
							<td>
                                <router-link tag="button" class="btn_blue_icon" :to='`/crm/tasks/edit/${cont.id}`'>
									<i data-feather="edit-2" class="sidebar_icon" ></i>
								</router-link>
								<button class="btn_red_icon" @click="deleteConts(cont.id)">
									<i data-feather="trash" class="sidebar_icon" ></i>
								</button>
							</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getTasks" @pagination-change-page="getResults"></pagination>
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
			await this.actionTasks()
            feather.replace()

		},
		computed:{
			...mapGetters('task',['getTasks','getMassage'])
		},
		methods:{
			...mapActions('task',['actionTasks']),
			async getResults(page = 1){
				await this.actionTasks(page)
			},
		}
	}
</script>
<style scoped>

</style>
