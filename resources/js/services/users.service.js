import ApiService from './api.service'

const UsersService = {
	allusers(page){
		return ApiService.get(`/api/user/all?page=${page}`)
	},
    addUser(data){
		return ApiService.post(`/api/user/store`, data);
	},
    editUser(data){
		return ApiService.get(`/api/user/edit/${data.id}`);
	},
    updateUser(data){
		return ApiService.post(`/api/user/update/${data.id}`, data);
	},
    structureList(){
		return ApiService.get(`/api/structure/list`)
	},
    positionList(data){
		return ApiService.post(`/api/position/list`, data)
	},
    positionList(data){
		return ApiService.post(`/api/position/list`, data)
	},
};
export { UsersService };
