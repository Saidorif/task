import {StateService} from "../services/mystate.service";

const state = {
	states: {},
	stateList: [],
	message: [],
	mystate: [],
};

const getters = {
	getStates(state){
		return state.states
	},
	getMassage(state){
		return state.message
	},
	getState(state){
		return state.mystate
	},
	getStateList(state){
		return state.stateList
	},
};


const actions = {
	async actionStateList({commit},page){
		try {
			const actions =  await StateService.stateList(page);
			await commit('setStateList',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionStates({commit},page){
		try {
			const actions =  await StateService.states(page);
			await commit('setStates',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddState({commit},payload){
		try {
			const actions =  await StateService.addState(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionDeleteState({commit},payload){
		try {
			const actions =  await StateService.removeState(payload);
			await commit('setMessage', actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditState({commit},payload){
		try {
			const actions =  await StateService.editState(payload);
			await commit('setEditState',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateState({commit},payload){
		try {
			const actions =  await StateService.updateState(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setStates(state, states){
		state.states = states
	},
	setMessage(state, message){
		state.message = message
	},
	setEditState(state, mystate){
		state.mystate = mystate
	},
    setStateList(state, stateList){
        state.stateList = stateList
    }
};

export const mystate = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
