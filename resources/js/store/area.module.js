import {AreaService} from "../services/area.service";

const state = {
	areas: {},
	message: [],
	area: [],
};

const getters = {
	getAreas(state){
		return state.areas
	},
	getMassage(state){
		return state.message
	},
	getArea(state){
		return state.area
	},
};


const actions = {
	async actionAreas({commit},page){
		try {
			const actions =  await AreaService.areas(page);
			await commit('setAreas',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddArea({commit},payload){
		try {
			const actions =  await AreaService.addArea(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionDeleteArea({commit},payload){
		try {
			const actions =  await AreaService.removeArea(payload);
			await commit('setMessage', actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditArea({commit},payload){
		try {
			const actions =  await AreaService.editArea(payload);
			await commit('setEditArea',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateArea({commit},payload){
		try {
			const actions =  await AreaService.updateArea(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setAreas(state, areas){
		state.areas = areas
	},
	setMessage(state, message){
		state.message = message
	},
	setEditArea(state, area){
		state.area = area
	},
};

export const area = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
