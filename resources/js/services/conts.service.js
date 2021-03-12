import ApiService from './api.service'

const ContsService = {
	contFindUrl(data){
		return ApiService.post(`/api/controller/find`, data)
	},
	conts(){
		return ApiService.get(`/api/controller`)
	},
	addCont(data){
		return ApiService.post(`/api/controller/store`,data)
	},
	editCont(id){
		return ApiService.get(`/api/controller/edit/${id}`)
	},
	updateCont(data){
		return ApiService.post(`/api/controller/update/${data.id}`,data)
	},
	allContsActions(){
		return ApiService.get(`/api/all-controller-actions`)
	},
	// deleteConts(id){
	// 	return ApiService.delete(`/api/controller/destroy/${id}`)
	// },
};

export { ContsService };
