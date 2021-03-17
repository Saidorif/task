import ApiService from './api.service'

const RegionService = {
	regions(){
		return ApiService.post(`/api/region`)
	},
	addRegion(data){
		return ApiService.post(`/api/region/store`,data)
	},
	editRegion(data){
		return ApiService.get(`/api/region/edit/${data.id}`)
	},
	updateRegion(data){
		return ApiService.post(`/api/region/update/${data.id}`,data)
	},
	removeRegion(id){
        return ApiService.delete(`/api/region/destroy/${id}`)
	},
};

export { RegionService };
