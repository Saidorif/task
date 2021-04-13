import ApiService from './api.service'

const DashboardService = {
	dashboardInfo(date){
		return date ? ApiService.get(`/api/dashboard?calendar=${date}`) : ApiService.get(`/api/dashboard`)
	},
	getTaskByDate(date){
		return  ApiService.post(`/api/task/get-by-date`, date)
	},

};

export { DashboardService };
