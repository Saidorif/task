import {UserTaskService} from "../services/usertask.service";

const state = {
	tasks: {},
	message: [],
	task: [],
};

const getters = {
	getUserTasks(state){
		return state.tasks
	},
	getMassage(state){
		return state.message
	},
	getUserTask(state){
		return state.task
	},
};


const actions = {
	async actionUserTasks({commit},page){
		try {
			const actions =  await UserTaskService.usertasks(page);
			await commit('setTasks',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionSendAnswer({commit},payload){
		try {
			const actions =  await UserTaskService.sendAnswer(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditUserTask({commit},payload){
		try {
			const actions =  await UserTaskService.editUserTask(payload);
			await commit('setEditTask',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateTask({commit},payload){
		try {
			const actions =  await UserTaskService.updateTask(payload);
			await commit('setEditTask',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
};

const mutations = {
	setTasks(state, tasks){
		state.tasks = tasks
	},
	setMessage(state, message){
		state.message = message
	},
	setEditTask(state, task){
		state.task = task
	},
};

export const usertask = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
