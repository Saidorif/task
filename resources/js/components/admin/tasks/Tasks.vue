<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Топшириқлар
			</h4>
            <router-link class="btn_green" to="/crm/tasks/add"><i data-feather="plus" class="sidebar_icon"></i>Топшириқ яратиш</router-link>
		</div>
        <div class="jv_card">
            <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col" style="width:40px;">№</th>
							<th scope="col">Қисқа мазмуни</th>
							<th scope="col">Ижрочилар</th>
							<th scope="col" style="width:100px">Муддати</th>
							<th scope="col" style="width:140px">Жорий холати</th>
							<th scope="col" style="width:110px">Тахрирлаш</th>
						</tr>
					</thead>
					<tbody v-if="getTasks.data && getTasks.data.length">
						 <tr v-for="(cont,index) in getTasks.data">
							<td style="width:40px;" scope="row">{{index+1}}</td>
							<td>{{cont.title}}</td>
							<td style="padding:0px;">
                                <ul>
                                    <li v-for="(item) in cont.users"  :class="item.svot == 1 ? 'active' : ''" v-tooltip.top-center="'<b>'+item.user.position.name+ '</b><br> ' + item.user.name+ ' '+ item.user.surename">
                                        {{item.user.position.structure.name}}
                                    </li>
                                </ul>
                            </td>
							<td>{{$g.getDate(cont.exp_date)}}</td>
							<td>
                                <span class="alert alert-danger jv_alert" v-if="cont.status == 'rejected'">Рад этилган</span>
                                <span class="alert alert-success jv_alert" v-if="cont.status == 'accepted'">Қабул қилинган</span>
                                <span class="alert alert-warning jv_alert" v-if="cont.status == 'pending'">Тасдиқланмаган</span>
                                <span class="alert alert-info jv_alert" v-if="cont.status == 'active'">Бажарилмоқда</span>
                                <span class="alert alert-dark jv_alert" v-if="cont.status == 'draft'">Режада</span>
                            </td>
							<td>
                                <router-link tag="button" class="btn_blue_icon" :to='`/crm/tasks/edit/${cont.id}`' v-tooltip.top-center="'Тахрирлаш'">
									<i data-feather="edit-2" class="sidebar_icon" ></i>
								</router-link>
								<button class="btn_red_icon" @click="deleteItem(cont.id)" v-tooltip.top-center="'Ўчириш'">
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
			...mapGetters('task',['getTasks','getTaskMassage'])
		},
		methods:{
			...mapActions('task',['actionTasks', 'actionDeleteTask']),
			async getResults(page = 1){
				await this.actionTasks(page)
			},
            async deleteItem(id){
                swal.fire({
                    type: 'confirm',
                    toast: false,
                    icon: 'question',
                    title: 'Вы действительно хотите удалить?',
                    confirmButtonText: `Удалить`,
                    cancelButtonText: `Отмена`,
                    showCancelButton: true
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let page = 1
                        await this.actionDeleteTask(id)
                        if(this.getTaskMassage.success){
                            await this.actionTasks(page)
                            toast.fire({
                                type: 'success',
                                icon: 'success',
                                title: this.getTaskMassage.message,
                            })
                        }
                    }
                })
			}
		}
	}
</script>
<style scoped>

</style>
