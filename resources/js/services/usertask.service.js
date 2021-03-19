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
	updateTask(data){
		return ApiService.post(`/api/usertask/update/${data.id}`,data)
	},
};

export { UserTaskService };
