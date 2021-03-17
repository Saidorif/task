<template>
	<div class="dashboard">
		<div class="page_header">
			<h4 class="header_title">
				Добро пожаловать в админ панель
			</h4>
		</div>
        <div class="jv_card"></div>
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
			},
			async clear(){

			}
		},
		async mounted(){
			// await this.actionDashboard(this.filter)
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
