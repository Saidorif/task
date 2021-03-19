import {TaskService} from "../services/task.service";

const state = {
	tasks: {},
	message: [],
	task: [],
};

const getters = {
	getTasks(state){
		return state.tasks
	},
	getMassage(state){
		return state.message
	},
	getTask(state){
		return state.task
	},
};


const actions = {
	async actionTasks({commit},page){
		try {
			const actions =  await TaskService.tasks(page);
			await commit('setTasks',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
	async actionAddTask({commit},payload){
		try {
			const actions =  await TaskService.addTask(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionEditTask({commit},payload){
		try {
			const actions =  await TaskService.editTask(payload);
			await commit('setEditTask',actions.data.result)
			return true
		} catch (error) {
			return false
		}
	},
    async actionDeleteTask({commit},payload){
		try {
			const actions =  await TaskService.removeTask(payload);
			await commit('setMessage', actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionUpdateTask({commit},payload){
		try {
			const actions =  await TaskService.updateTask(payload);
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

export const task = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
