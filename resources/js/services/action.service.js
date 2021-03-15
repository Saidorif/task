import ApiService from './api.service'

const ActionService = {
	actions(){
		return ApiService.get(`/api/action`)
	},
	addAction(data){
		return ApiService.post(`/api/action/store`,data)
	},
	editAction(data){
		return ApiService.get(`/api/action/edit/${data.id}`)
	},
	updateAction(data){
		return ApiService.post(`/api/action/update/${data.id}`,data)
	},
};

export { ActionService };
