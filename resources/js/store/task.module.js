import {TaskService} from "../services/task.service";

const state = {
	tasks: {},
	tasklist: {},
	message: [],
	task: [],
};

const getters = {
	getTasks(state){
		return state.tasks
	},
	getTaskMassage(state){
		return state.message
	},
	getTask(state){
		return state.task
	},
	getTaskList(state){
		return state.tasklist
	},
};


const actions = {
	async actionTasks({commit},payload){
		try {
			const actions =  await TaskService.tasks(payload);
			await commit('setTasks',actions.data.result)
			await commit('setTaskList',actions.data.downloads)
			return true
		} catch (error) {
			return false
		}
	},
	async actionImportantTask({commit},payload){
		try {
			const actions =  await TaskService.importantTask(payload);
			await commit('setTasks',actions.data.result)
			await commit('setTaskList',actions.data.downloads)
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
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
    async actionAcceptTask({commit},payload){
		try {
			const actions =  await TaskService.acceptTask(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionRejectTask({commit},payload){
		try {
			const actions =  await TaskService.rejectTask(payload);
			await commit('setMessage',actions.data)
			return true
		} catch (error) {
			return false
		}
	},
	async actionApproveTask({commit},payload){
		try {
			const actions =  await TaskService.approveTask(payload);
			await commit('setMessage',actions.data)
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
	setTaskList(state, task){
		state.tasklist = task
	},
};

export const task = {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
