import {PermissionService} from "../services/permission.service";

const state = {
	permissions: [],
	message: [],
};

const getters = {
	getPermissions(state){
		return state.permissions
	},
	getMassage(state){
		return state.message
	},
};


const actions = {
	async actionPermission({commit},payload){
		try {
			const permissions =  await PermissionService.permissions(payload);
			await commit('setPermission',permissions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionPermissionStore({commit},payload){
		try {
			const permissions =  await PermissionService.permissionStore(payload);
			await commit('setMessage',permissions.data)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setPermission(state, permissions){
		state.permissions = permissions
	},
	setMessage(state, message){
		state.message = message
	},
};

export const permission = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
