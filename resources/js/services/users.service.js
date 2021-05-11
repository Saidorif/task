import ApiService from './api.service'

const UsersService = {
	allusers(page){
		return ApiService.get(`/api/user/all?page=${page}`)
	},
    addUser(data){
		return ApiService.post(`/api/user/store`, data);
	},
    structureList(){
		return ApiService.get(`/api/structure/list`)
	},
};

export { UsersService };
