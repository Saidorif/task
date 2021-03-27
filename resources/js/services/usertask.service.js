import ApiService from './api.service'

const UserTaskService = {
	usertasks(){
		return ApiService.get(`/api/task/user`)
	},
	sendAnswer(data){
		return ApiService.fileSend(`/api/usertask/store`,data)
	},
	editUserTask(data){
		return ApiService.get(`/api/usertask/edit/${data.id}`)
	},
	updateUserTask(data){
		return ApiService.fileSend(`/api/usertask/update/${data.id}`,data.data)
	},
	acceptTaskSvot(data){
		return ApiService.post(`/api/usertask/accept/${data.id}`)
	},
	rejectTaskSvot(data){
		return ApiService.post(`/api/usertask/reject/${data.id}`, data)
	},
	approveTaskSvot(data){
		return ApiService.post(`/api/usertask/approve/${data.id}`, data)
	},
};

export { UserTaskService };
