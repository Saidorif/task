import {DailyjobService} from "../services/dailyjob.service";

const state = {
	jobs: {},
	message: [],
	area: [],
    job:{},
};

const getters = {
	getJobs(state){
		return state.jobs
	},
	getMassage(state){
		return state.message
	},
	getJob(state){
		return state.job
	},
};


const actions = {
	async actionJobs({commit},payload){
		try {
			const actions =  await DailyjobService.jobs(payload);
			await commit('setJobs',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddJob({commit},payload){
		try {
			const actions =  await DailyjobService.addJob(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionDeleteJob({commit},payload){
		try {
			const actions =  await DailyjobService.removeJob(payload);
			await commit('setMessage', actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditJob({commit},payload){
		try {
			const actions =  await DailyjobService.editJob(payload);
			await commit('setEditJob',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateJob({commit},payload){
		try {
			const actions =  await DailyjobService.updateJob(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setJobs(state, jobs){
		state.jobs = jobs
	},
	setMessage(state, message){
		state.message = message
	},
	setEditJob(state, job){
		state.job = job
	},
};

export const dailyjob = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
