import {RegionService} from "../services/region.service";

const state = {
	regions: {},
	message: [],
	region: [],
};

const getters = {
	getRegions(state){
		return state.regions
	},
	getMassage(state){
		return state.message
	},
	getRegion(state){
		return state.region
	},
};


const actions = {
	async actionRegions({commit},page){
		try {
			const actions =  await RegionService.regions(page);
			await commit('setRegions',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddRegion({commit},payload){
		try {
			const actions =  await RegionService.addRegion(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionDeleteRegion({commit},payload){
		try {
			const actions =  await RegionService.removeRegion(payload);
			await commit('setMessage', actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditRegion({commit},payload){
		try {
			const actions =  await RegionService.editRegion(payload);
			await commit('setEditRegion',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateRegion({commit},payload){
		try {
			const actions =  await RegionService.updateRegion(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setRegions(state, regions){
		state.regions = regions
	},
	setMessage(state, message){
		state.message = message
	},
	setEditRegion(state, region){
		state.region = region
	},
};

export const region = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
