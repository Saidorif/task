import {ActionService} from "../services/action.service";

const state = {
	actions: {},
	message: [],
	action: [],
};

const getters = {
	getActions(state){
		return state.actions
	},
	getMassage(state){
		return state.message
	},
	getAction(state){
		return state.action
	},
};


const actions = {
	async actionActions({commit},page){
		try {
			const actions =  await ActionService.actions(page);
			await commit('setActions',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddAction({commit},payload){
		try {
			const actions =  await ActionService.addAction(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditAction({commit},payload){
		try {
			const actions =  await ActionService.editAction(payload);
			await commit('setEditAction',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateAction({commit},payload){
		try {
			const actions =  await ActionService.updateAction(payload);
			await commit('setEditAction',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setActions(state, actions){
		state.actions = actions
	},
	setMessage(state, message){
		state.message = message
	},
	setEditAction(state, action){
		state.action = action
	},
};

export const action = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
