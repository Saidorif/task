import ApiService from './api.service'

const StateService = {
	stateList(){
		return ApiService.get(`/api/state/list`)
	},
	states(){
		return ApiService.post(`/api/state/tree`)
	},
	addState(data){
		return ApiService.post(`/api/state/store`,data)
	},
	editState(data){
		return ApiService.get(`/api/state/edit/${data.id}`)
	},
	updateState(data){
		return ApiService.post(`/api/state/update/${data.id}`,data)
	},
	removeState(id){
        return ApiService.delete(`/api/state/destroy/${id}`)
	},
};

export { StateService };
