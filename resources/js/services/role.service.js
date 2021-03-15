import ApiService from './api.service'

const RoleService = {
	roleList(){
		return ApiService.get(`/api/role/list`)
	},
	roles(page){
		return ApiService.get(`/api/role?page=`+ page)
	},
	addRole(data){
		return ApiService.post(`/api/role/store`,data)
	},
	editRole(data){
		return ApiService.get(`/api/role/edit/${data.id}`,data)
	},
	updateRole(data){
		return ApiService.post(`/api/role/update/${data.id}`,data)
	},
	deleteRole(id){
		return ApiService.delete(`/api/role/destroy/${id}`)
	},
};

export { RoleService };
