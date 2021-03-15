import ApiService from './api.service'

const PermissionService = {
	permissions(data){
		return ApiService.post(`/api/permissions`,data)
	},
	permissionStore(data){
		return ApiService.post(`/api/permissions/store/${data.id}`,data)
	},
};

export { PermissionService };
