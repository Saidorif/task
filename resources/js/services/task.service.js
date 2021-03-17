import ApiService from './api.service'

const TaskService = {
	tasks(){
		return ApiService.get(`/api/task`)
	},
	addTask(data){
		return ApiService.post(`/api/task/store`,data)
	},
	editTask(data){
		return ApiService.get(`/api/task/edit/${data.id}`)
	},
	updateTask(data){
		return ApiService.post(`/api/task/update/${data.id}`,data)
	},
};

export { TaskService };
