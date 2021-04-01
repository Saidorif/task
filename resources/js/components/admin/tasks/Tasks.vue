<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Топшириқлар
			</h4>
            <div class="d_flex">
                <button type="button" class="btn_blue mr_15"><i data-feather="filter" class="sidebar_icon"></i> Саралаш</button>
                <router-link class="btn_green" to="/crm/tasks/add"><i data-feather="plus" class="sidebar_icon"></i>Топшириқ яратиш</router-link>
            </div>
		</div>
        <div class="jv_card filter">
            <div class="input_style col-md-3">
                <date-picker
                range
                v-model="filter_date"
                placeholder="Оралиқ сана"
                value-type="format"
                format="DD.MM.YYYY"
                ></date-picker>
            </div>
            <div class="input_style col-md-3 mr_15">
                <select v-model="filter.status" id="status">
                    <option  value="">Холатни танланг</option>
                    <option  value="rejected">Рад этилган</option>
                    <option  value="accepted">Қабул қилинган</option>
                    <option  value="pending">Тасдиқланмаган</option>
                    <option  value="active">Бажарилмоқда</option>
                    <option  value="draft">Режада</option>
                </select>
                <label for="status">Холат</label>
            </div>
            <button type="button" class="btn_black" @click="filterDate"><i data-feather="filter" class="sidebar_icon"></i> Саралаш</button>
        </div>
        <div class="jv_card">
            <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col" style="width:40px;">№</th>
							<th scope="col">Қисқа мазмуни</th>
							<th scope="col" style="width: 30%;">Масъул ижрочи</th>
							<th scope="col" style="width:100px">Муддати</th>
							<th scope="col" style="width:140px">Жорий холати</th>
							<th scope="col" style="width:110px">Тахрирлаш</th>
						</tr>
					</thead>
					<tbody v-if="getTasks.data && getTasks.data.length" class="hide_users">
						 <tr v-for="(cont,index) in getTasks.data">
							<td style="width:40px;" scope="row">{{index+1}}</td>
							<td>{{cont.title}}</td>
							<td style="padding:0px;">
                                <ul class="userList">
                                    <li v-for="(item) in cont.users" @click="showAllUsers(cont)"  :class="item.svot == 1 ? 'active' : ''" v-tooltip.top-center="'<b>'+item.user.position.name+ '</b><br> ' + item.user.name+ ' '+ item.user.surename">
                                        {{item.user.position.structure.name}} {{ cont.isOpen}}
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
    import DatePicker from "vue2-datepicker";
	export default{
        components:{
            DatePicker
        },
		data(){
			return{
                filter: {
                    status: '',
                    date_from: '',
                    date_to: '',
                },
                filter_date: '',
			}
		},
		async mounted(){
			await this.actionTasks({page: 1, filter:this.filter})
            feather.replace()
            $('.userList').on('click', function(){
                $(this).toggleClass('active')
            })
		},
		computed:{
			...mapGetters('task',['getTasks','getTaskMassage'])
		},
        watch: {
            filter_date: function(val){
                this.filter.date_from = val[0]
                this.filter.date_to = val[1]
            },
        },
		methods:{
			...mapActions('task',['actionTasks', 'actionDeleteTask']),
			async getResults(page = 1){
				await this.actionTasks({page: page})
			},
            async filterDate(){
                await this.actionTasks({page: 1, filter:this.filter})
            },
            showAllUsers(con){
                if(con.isOpen){
                    con.isOpen = false
                }else{
                    con.isOpen = true
                }
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
