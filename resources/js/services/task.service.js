import ApiService from './api.service'

const TaskService = {
	tasks(){
		return ApiService.get(`/api/task`)
	},
	addTask(data){
		return ApiService.fileSend(`/api/task/store`,data)
	},
	editTask(data){
		return ApiService.get(`/api/task/edit/${data.id}`)
	},
	updateTask(data){
		return ApiService.fileSend(`/api/task/update/${data.id}`,data.data)
	},
    removeTask(id){
        return ApiService.delete(`/api/task/destroy/${id}`)
	},
    acceptTask(data){
		return ApiService.post(`/api/task/accept/${data.id}`)
	},
	rejectTask(data){
		return ApiService.post(`/api/task/reject/${data.id}`, data)
	},
	approveTask(data){
		return ApiService.post(`/api/task/approve/${data.id}`,)
	},
};

export { TaskService };
