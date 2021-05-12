import {UsersService} from "../services/users.service";

const state = {
	allusers: {},
    message:{},
    structureList:{},
    positionList:{},
    user:{},
};

const getters = {
	getAllUsers(state){
		return state.allusers
	},
    getStructureList(state){
		return state.structureList
    },
    getPositionList(state){
		return state.positionList
    },
	getMessage(state){
		return state.message
	},
    getUser(state){
        return state.user
    }
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
	async actionPositionList({commit}, payload){
		try {
			const allusers =  await UsersService.positionList(payload);
			await commit('setPositionList',allusers.data)
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
    async ActionEditUser({commit}, id){
		try {
			let sendData = await UsersService.editUser(id);
			await commit('setUser',sendData.data)
			return true
		}catch(e){
			return false
		}
	},
    async ActionUpdateUser({commit}, payload){
		try {
			let sendData = await UsersService.updateUser(payload);
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
	setPositionList(state, positionList){
		state.positionList = positionList
	},
	setMessage(state, mesg){
		state.message = mesg
	},
    setUser(state, user){
        return state.user = user
    }
};

export const users = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
