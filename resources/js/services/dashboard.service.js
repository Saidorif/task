import ApiService from './api.service'

const DashboardService = {
	dashboardInfo(date){
		return date ? ApiService.get(`/api/dashboard?calendar=${date}`) : ApiService.get(`/api/dashboard`)
	},
};

export { DashboardService };
