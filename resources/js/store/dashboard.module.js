import {DashboardService} from "../services/dashboard.service";

const state = {
	dashboard: [],
    task:[],
};

const getters = {
	getDashboard(state){
		return state.dashboard
	},
    getTask(state){
		return state.task
    }
};


const actions = {
	async actionDashboard({commit}, payload){
		try {
			const dashboard =  await DashboardService.dashboardInfo(payload);
			await commit('setDashboard',dashboard.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionTaskByDate({commit}, payload){
		try {
			const dashboard =  await DashboardService.getTaskByDate(payload);
			await commit('setTask',dashboard.data)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setDashboard(state, dashboard){
		state.dashboard = dashboard
	},
	setTask(state, task){
		state.task = task
	},
};

export const dashboard = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
