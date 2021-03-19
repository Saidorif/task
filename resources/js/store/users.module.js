import {UsersService} from "../services/users.service";

const state = {
	allusers: {},
};

const getters = {
	getAllUsers(state){
		return state.allusers
	},
};


const actions = {
	async actionAllUsers({commit}, payload){
		try {
			const allusers =  await UsersService.allusers(payload);
			await commit('setAllUsers',allusers.data.result)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setAllUsers(state, allusers){
		state.allusers = allusers
	},
};

export const users = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
