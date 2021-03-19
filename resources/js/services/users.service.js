import ApiService from './api.service'

const UsersService = {
	allusers(page){
		return ApiService.get(`/api/user/all?page=${page}`)
	},
};

export { UsersService };
