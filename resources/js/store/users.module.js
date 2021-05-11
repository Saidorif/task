import {UsersService} from "../services/users.service";

const state = {
	allusers: {},
    message:{},
    structureList:{},
};

const getters = {
	getAllUsers(state){
		return state.allusers
	},
    getStructureList(state){
		return state.structureList
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
	async actionStructureList({commit}){
		try {
			const allusers =  await UsersService.structureList();
			await commit('setStructureList',allusers.data)
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
	setStructureList(state, structureList){
		state.structureList = structureList
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
