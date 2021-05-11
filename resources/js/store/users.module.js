import {UsersService} from "../services/users.service";

const state = {
	allusers: {},
    message:null,
};

const getters = {
	getAllUsers(state){
		return state.allusers
	},
	getMessage(state){
		return state.message
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
    async ActionAddUser({commit}, data){
		try {
			let sendData = await UsersService.addUser(data);
			await commit('setMessage',sendData.data)
			return true
		}catch(e){
			return false
		}
	},
};

const mutations = {
	setAllUsers(state, allusers){
		state.allusers = allusers
	},
	setMessage(state, mesg){
		state.message = mesg
	},
};

export const users = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
