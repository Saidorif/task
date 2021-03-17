<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				Region
			</h4>
            <router-link class="btn_green" to="/crm/region/add"><i data-feather="plus" class="sidebar_icon"></i> Добавить</router-link>
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
					<tbody v-if="getRegions.data && getRegions.data.length">
						 <tr v-for="(cont,index) in getRegions.data">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.name}}</td>
							<td>
								<router-link tag="button" class="btn_blue_icon" :to='`/crm/region/edit/${cont.id}`'>
									<i data-feather="edit-2" class="sidebar_icon" ></i>
								</router-link>
								<button class="btn_red_icon" @click="deleteItem(cont.id)">
									<i data-feather="trash" class="sidebar_icon" ></i>
								</button>
							</td>
						</tr>
					</tbody>
					<pagination :limit="4" :data="getRegions" @pagination-change-page="getResults"></pagination>
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
			await this.actionRegions()
            feather.replace()

		},
		computed:{
			...mapGetters('region',['getRegions','getMassage'])
		},
		methods:{
			...mapActions('region',['actionRegions', 'actionDeleteRegion']),
			async getResults(page = 1){
				await this.actionRegions(page)
			},
            async deleteItem(id){
                    // title: 'Do you want to save the changes?',
                    // showDenyButton: true,
                    // showCancelButton: true,
                    // confirmButtonText: `Save`,
                    // denyButtonText: `Don't save`,
				if(confirm("Вы действительно хотите удалить?")){
					let page = 1
					await this.actionDeleteRegion(id)
                    if(this.getMassage.success){
					    await this.actionRegions(page)
                        toast.fire({
                            type: 'success',
                            icon: 'success',
                            title: this.getMassage.message,
                        })
                    }

				}
			}
		}
	}
</script>
<style scoped>

</style>
