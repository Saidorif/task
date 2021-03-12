import {RoleService} from "../services/role.service";

const state = {
	roles: {},
	roleList: [],
	message: [],
	role: [],
};

const getters = {
	getRoles(state){
		return state.roles
	},
	getRoleList(state){
		return state.roleList
	},
	getMassage(state){
		return state.message
	},
	getRole(state){
		return state.role
	},
};


const actions = {
	async actionRoleList({commit},){
		try {
			const roles =  await RoleService.roleList();
			await commit('setRoleList',roles.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionRoles({commit},page){
		try {
			const roles =  await RoleService.roles(page);
			await commit('setRoles',roles.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddRole({commit},payload){
		try {
			const roles =  await RoleService.addRole(payload);
			await commit('setMessage',roles.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditRole({commit},payload){
		try {
			const roles =  await RoleService.editRole(payload);
			await commit('setEditRole',roles.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateRole({commit},payload){
		try {
			const roles =  await RoleService.updateRole(payload);
			await commit('setEditRole',roles.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionDeleteRole({commit},id){
		try {
			const roles =  await RoleService.deleteRole(id);
			await commit('setMessage',roles.data.result)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setRoleList(state, roles){
		state.roleList = roles
	},
	setRoles(state, roles){
		state.roles = roles
	},
	setMessage(state, message){
		state.message = message
	},
	setEditRole(state, role){
		state.role = role
	},
};

export const role = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
