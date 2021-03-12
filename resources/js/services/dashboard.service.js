import ApiService from './api.service'

const DashboardService = {
	dashboardChart(data){
		return ApiService.get(`/api/dashboard?date_from=${data.date_from ? data.date_from : ''}&date_to=${data.date_to ? data.date_to : ''}`)
	},
};

export { DashboardService };
