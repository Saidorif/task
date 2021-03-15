<template>
	<div class="dashboard">
		<div class="card">
		  	<div class="card-header">
				  <div class="header_title">
						<h4 class="title_user">
							<i class="sidebar_icon fas fa-desktop"></i>
							Админ панель
						</h4>
					<div class="add_user_btn">
			            <button type="button" class="btn btn-info toggleFilter" @click.prevent="toggleFilter">
						    <i class="fas fa-filter"></i>
			            	Филтр
						</button>
		            </div>
				  </div>
		  	</div>
		  	<div class="card-body">
	  			<div class="row">
	  			</div>
		  	</div>
	  	</div>
	</div>
</template>
<script>

	import {mapActions, mapGetters} from 'vuex'
	export default{
		data(){
			return{
				loaded: false,
				vedGroupChartData: {labels: [], datas_ex: [], data_im: [], data_total: []},
				vedGroupChartDataSettings: [
					{label: 'Экспорт', backgroundColor: '#01163880', borderWidth: 1, borderColor: '#011638', data: 'datas_ex', chartTitle: 'Отчет по продуктам (тонн)'},
					{label: 'Импорт', backgroundColor: '#2C423F80', borderWidth: 1, borderColor: '#2C423F', data: 'data_im'},
					{label: 'Общий', backgroundColor: '#7F95D180', borderWidth: 1, borderColor: '#7F95D1', data: 'data_total'}
				],
				vedGroupChartDataByCountry: {labels: [], datas_ex: [], data_im: [], data_total: []},
				vedGroupChartDataByCountrySettings: [
					{label: 'Экспорт', backgroundColor: '#FF842780', borderWidth: 1, borderColor: '#FF8427', data: 'datas_ex', chartTitle: 'Отчет по продуктам (сумма)'},
					{label: 'Импорт', backgroundColor: '#4D8B3180', borderWidth: 1, borderColor: '#4D8B31', data: 'data_im'},
					{label: 'Общий', backgroundColor: '#7F95D180', borderWidth: 1, borderColor: '#7F95D1', data: 'data_total'}
				],

				countryChartData: {labels: [], datas_ex: [], data_im: [], data_total: []},
				countryChartDataSettings: [
					{label: 'Экспорт', backgroundColor: '#01163880', borderWidth: 1, borderColor: '#011638', data: 'datas_ex', chartTitle: 'Отчет по странам (тонн)'},
					{label: 'Импорт', backgroundColor: '#2C423F80', borderWidth: 1, borderColor: '#2C423F', data: 'data_im'},
					{label: 'Общий', backgroundColor: '#7F95D180', borderWidth: 1, borderColor: '#7F95D1', data: 'data_total'}
				],
				countryChartDataByCountry: {labels: [], datas_ex: [], data_im: [], data_total: []},
				countryChartDataByCountrySettings: [
					{label: 'Экспорт', backgroundColor: '#FF842780', borderWidth: 1, borderColor: '#FF8427', data: 'datas_ex', chartTitle: 'Отчет по странам (сумма)'},
					{label: 'Импорт', backgroundColor: '#4D8B3180', borderWidth: 1, borderColor: '#4D8B31', data: 'data_im'},
					{label: 'Общий', backgroundColor: '#7F95D180', borderWidth: 1, borderColor: '#7F95D1', data: 'data_total'}
				],
				filter: {
					date_to: '',
					date_from: '',
				},
				filterShow:false,
			}
		},
		computed:{
			...mapGetters('dashboard',['getDashboard'])
		},
		methods:{
			...mapActions('dashboard',['actionDashboard']),
			toggleFilter(){
				this.filter.date_from = ''
				this.filter.date_to = ''
				this.filterShow = !this.filterShow
			},
			async search(){
				await this.actionDashboard(this.filter);
				this.vedGroupChartData = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.vedGroupChartDataByCountry = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.countryChartData = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.countryChartDataByCountry = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.getDashboard.data_vedcode.forEach(element => {
					if(!this.vedGroupChartData.labels.includes(element.name)){
						this.vedGroupChartData.labels.push(element.name)
						this.vedGroupChartData.data_im.push(element.im_total_weight)
						this.vedGroupChartData.datas_ex.push(element.ek_total_weight)
						this.vedGroupChartData.data_total.push(element.total_weight)
						this.vedGroupChartDataByCountry.labels.push(element.name)
						this.vedGroupChartDataByCountry.data_im.push(element.im_total_cost)
						this.vedGroupChartDataByCountry.datas_ex.push(element.im_eks_total_cost)
						this.vedGroupChartDataByCountry.data_total.push(element.total_cost)
					}
				});
				this.getDashboard.data_country.forEach(element => {
					if(!this.countryChartData.labels.includes(element.name)){
						if(element.total_weight){
							this.countryChartData.labels.push(element.name)
							this.countryChartData.data_im.push(element.im_total_weight)
							this.countryChartData.datas_ex.push(element.ek_total_weight)
							this.countryChartData.data_total.push(element.total_weight)
							this.countryChartDataByCountry.labels.push(element.name)
							this.countryChartDataByCountry.data_im.push(element.im_total_cost)
							this.countryChartDataByCountry.datas_ex.push(element.im_eks_total_cost)
							this.countryChartDataByCountry.data_total.push(element.total_cost)
						}
					}
				});
			},
			async clear(){
				this.filter.date_to = '';
				this.filter.date_from = '';
				await this.actionDashboard(this.filter);
				this.vedGroupChartData = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.vedGroupChartDataByCountry = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.countryChartData = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.countryChartDataByCountry = {labels: [], datas_ex: [], data_im: [], data_total: []},
				this.getDashboard.data_vedcode.forEach(element => {
					if(!this.vedGroupChartData.labels.includes(element.name)){
						this.vedGroupChartData.labels.push(element.name)
						this.vedGroupChartData.data_im.push(element.im_total_weight)
						this.vedGroupChartData.datas_ex.push(element.ek_total_weight)
						this.vedGroupChartData.data_total.push(element.total_weight)
						this.vedGroupChartDataByCountry.labels.push(element.name)
						this.vedGroupChartDataByCountry.data_im.push(element.im_total_cost)
						this.vedGroupChartDataByCountry.datas_ex.push(element.im_eks_total_cost)
						this.vedGroupChartDataByCountry.data_total.push(element.total_cost)
					}
				});
				this.getDashboard.data_country.forEach(element => {
					if(!this.countryChartData.labels.includes(element.name)){
						if(element.total_weight){
							this.countryChartData.labels.push(element.name)
							this.countryChartData.data_im.push(element.im_total_weight)
							this.countryChartData.datas_ex.push(element.ek_total_weight)
							this.countryChartData.data_total.push(element.total_weight)
							this.countryChartDataByCountry.labels.push(element.name)
							this.countryChartDataByCountry.data_im.push(element.im_total_cost)
							this.countryChartDataByCountry.datas_ex.push(element.im_eks_total_cost)
							this.countryChartDataByCountry.data_total.push(element.total_cost)
						}
					}
				});
			}
		},
		async mounted(){
			await this.actionDashboard(this.filter)
			this.getDashboard.data_vedcode.forEach(element => {
				if(!this.vedGroupChartData.labels.includes(element.name)){
					this.vedGroupChartData.labels.push(element.name)
					this.vedGroupChartData.data_im.push(element.im_total_weight)
					this.vedGroupChartData.datas_ex.push(element.ek_total_weight)
					this.vedGroupChartData.data_total.push(element.total_weight)
					this.vedGroupChartDataByCountry.labels.push(element.name)
					this.vedGroupChartDataByCountry.data_im.push(element.im_total_cost)
					this.vedGroupChartDataByCountry.datas_ex.push(element.im_eks_total_cost)
					this.vedGroupChartDataByCountry.data_total.push(element.total_cost)
				}
			});
			this.getDashboard.data_country.forEach(element => {
				if(!this.countryChartData.labels.includes(element.name)){
					if(element.total_weight){
						this.countryChartData.labels.push(element.name)
						this.countryChartData.data_im.push(element.im_total_weight)
						this.countryChartData.datas_ex.push(element.ek_total_weight)
						this.countryChartData.data_total.push(element.total_weight)
						this.countryChartDataByCountry.labels.push(element.name)
						this.countryChartDataByCountry.data_im.push(element.im_total_cost)
						this.countryChartDataByCountry.datas_ex.push(element.im_eks_total_cost)
						this.countryChartDataByCountry.data_total.push(element.total_cost)
					}
				}
			});
			this.loaded = true
		},
	}
</script>
<style scoped>
	.mw_5000{
		min-width: 5000px;
	}
	.overauto{
		overflow: auto;
	}
</style>
