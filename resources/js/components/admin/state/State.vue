<template>
	<div class="action">
        <div class="page_header">
			<h4 class="header_title">
				State
			</h4>
            <router-link class="btn_green" to="/crm/state/add"><i data-feather="plus" class="sidebar_icon"></i> Добавить</router-link>
		</div>
        <div class="jv_card">
            <!-- <div class="table-responsive">
				<table class="table table-bordered text-center table-hover table-striped">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Название</th>
							<th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody v-if="getStates.data && getStates.length">
						 <tr v-for="(cont,index) in getStates">
							<td scope="row">{{index+1}}</td>
							<td>{{cont.name}}</td>
							<td>
								<router-link tag="button" class="btn_blue_icon" :to='`/crm/state/edit/${cont.id}`'>
									<i data-feather="edit-2" class="sidebar_icon" ></i>
								</router-link>
								<button class="btn_red_icon" @click="deleteItem(cont.id)">
									<i data-feather="trash" class="sidebar_icon" ></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div> -->
            <div class="row">
                <div class="input_style col-4">
                    <select class="form-control input_style" id="layoutType" v-model="tree_config.layoutType">
                        <option value="horizontal">Горизонтальный</option>
                        <option value="vertical">Вертикальный</option>
                    </select>
                </div>
                <div class="input_style col-4">
                    <select class="form-control input_style" id="linkLayout" v-model="tree_config.linkLayout">
                        <option value="bezier">Безье</option>
                        <option value="orthogonal">Ортогональный</option>
                    </select>
                </div>
                <div class="input_style col-4">
                    <select class="form-control input_style" id="type" v-model="tree_config.type">
                        <option value="tree">Дерево</option>
                        <option value="cluster">Кластер</option>
                    </select>
                </div>
            </div>

            <div v-if="loaded" class="tree_block_cont">
                <!-- <organization-chart :datasource="getStates[0]" :zoom="true"></organization-chart> -->
                <tree :data="getStates[0]" @clickedText="tree_item_text($event)" :type="tree_config.type" node-text="name" :layoutType="tree_config.layoutType" :linkLayout="tree_config.linkLayout" :radius="6" :zoomable="tree_config.zoomable" popUpPlacement="bottom-start"></tree>
            </div>
        </div>
	</div>
</template>
<script>
	import {mapActions,mapGetters} from 'vuex'
    import OrganizationChart from 'vue-organization-chart'
    import 'vue-organization-chart/dist/orgchart.css'
    import {tree} from 'vued3tree'
	export default{
        components: {
            OrganizationChart,
            tree
	  	},
		data(){
			return{
                loaded:false,
                tree_config:{
                    layoutType: 'horizontal',
                    linkLayout: 'bezier',
                    type: 'tree',
                    zoomable: false,
                }

			}
		},
		async mounted(){
			await this.actionStates()
            feather.replace()
            this.loaded = true
		},
		computed:{
			...mapGetters('mystate',['getStates','getMassage'])
		},
		methods:{
			...mapActions('mystate',['actionStates', 'actionDeleteState']),
            tree_item_text(el){
                this.$router.push(`/crm/state/edit/${el.data.id}`);
            },
			async getResults(page = 1){
				await this.actionStates(page)
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
                        await this.actionDeleteState(id)
                        if(this.getMassage.success){
                            await this.actionStates(page)
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
    .viewport.treeclass,
    .tree_block_cont{
        min-height: 80vh;
    }
</style>
