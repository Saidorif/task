import {UserService} from "../services/user.service";

const state = {
	message: null,
	profile:[]
};

const getters = {
	getMessage(state){
		return state.message
	},
	getProfile(state){
		return state.profile
	}
};


const actions = {
	async ActionUpdatePassword({commit}, data){
		try {
			let sendData = await UserService.updatePassword(data);
			await commit('setMessage',sendData.data)
			return true
		}catch(e){
			return false
		}
	},	
	async ActionProfile({commit}){
		try {
			let sendData = await UserService.profileUser();
			await commit('setProfile',sendData.data.result)
			return true
		}catch(e){
			return false
		}
	},
	async ActionProfileUpdate({commit},payload){
		try {
			let sendData = await UserService.profileUpdate(payload);
			await commit('setMessage',sendData.data)
			return true
		}catch(e){
			return false
		}
	},
	async ActionChangePassword({commit},payload){
		try {
			let sendData = await UserService.changePass(payload);
			await commit('setMessage',sendData.data)
			return true
		}catch(e){
			return false
		}
	},
};

const mutations = {
	setMessage(state, data){
		state.message = data
	},
	setProfile(state, data){
		state.profile = data
	}
};

export const user = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
