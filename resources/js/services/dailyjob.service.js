import ApiService from './api.service'

const DailyjobService = {
	jobslist(){
		return ApiService.post(`/api/dailyjob/list`)
	},
	jobs(data){
		return ApiService.post(`/api/dailyjob?page=${data.page}`, data)
	},
	addJob(data){
		return ApiService.post(`/api/dailyjob/store`,data)
	},
	editJob(data){
		return ApiService.get(`/api/dailyjob/edit/${data.id}`)
	},
	updateJob(data){
		return ApiService.post(`/api/dailyjob/update/${data.id}`,data)
	},
	removeJob(id){
        return ApiService.delete(`/api/dailyjob/destroy/${id}`)
	},
};

export { DailyjobService };
