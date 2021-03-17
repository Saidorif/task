import ApiService from './api.service'

const AreaService = {
	areas(){
		return ApiService.post(`/api/area`)
	},
	addArea(data){
		return ApiService.post(`/api/area/store`,data)
	},
	editArea(data){
		return ApiService.get(`/api/area/edit/${data.id}`)
	},
	updateArea(data){
		return ApiService.post(`/api/area/update/${data.id}`,data)
	},
	removeArea(id){
        return ApiService.delete(`/api/area/destroy/${id}`)
	},
};

export { AreaService };
