<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Area
			</h4>
            <router-link class="btn_green" to="/crm/area/add"><i data-feather="plus" class="sidebar_icon"></i> Добавить</router-link>
		</div>
        <div class="jv_card">
            <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Название</th>
							<th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody v-if="getAreas.data && getAreas.data.length">
						 <tr v-for="(cont,index) in getAreas.data">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.name}}</td>
							<td>
								<router-link tag="button" class="btn_blue_icon" :to='`/crm/area/edit/${cont.id}`'>
									<i data-feather="edit-2" class="sidebar_icon" ></i>
								</router-link>
								<button class="btn_red_icon" @click="deleteItem(cont.id)">
									<i data-feather="trash" class="sidebar_icon" ></i>
								</button>
							</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getAreas" @pagination-change-page="getResults"></pagination>
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
			await this.actionAreas()
            feather.replace()

		},
		computed:{
			...mapGetters('area',['getAreas','getMassage'])
		},
		methods:{
			...mapActions('area',['actionAreas', 'actionDeleteArea']),
			async getResults(page = 1){
				await this.actionAreas(page)
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
                        await this.actionDeleteArea(id)
                        if(this.getMassage.success){
                            await this.actionAreas(page)
                            toast.fire({
                                type: 'success',
                                icon: 'success',
                                title: this.getMassage.message,
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
