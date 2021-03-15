import ApiService from './api.service'
import { TokenService } from './storage.service'

class AuthenticationError extends Error{
	constructor (errorCode,message){
		super(message)
		this.name = this.constructor.name
		this.errorCode = errorCode
	}
}

const UserService = {
	/**
	 * Login the user and store the access token to TokenService.
	 *
	 * @returns access_token
	 * @throws AuthenticationError
	 **/
	login: async function (userData){
		const requestData = {
			method: 'post',
			url: '/api/login',
			data: {
				email: userData.email,
				password: userData.password
			}
		};

		try{
			const response = await ApiService.customRequest(requestData);
			TokenService.saveToken(response.data.token);
			ApiService.setHeader();
			ApiService.mount401Interceptor();
			return response;
		} catch (error){
			throw new AuthenticationError(error.data.status, error.data.message);
		}
	},
	/**
	 * REGISTER the user and store the access token to TokenService.
	 *
	 * @returns access_token
	 * @throws AuthenticationError
	 **/
	register: async function (userData){
		const requestData = {
			method: 'post',
			url: '/api/register',
			data: userData
		};

		try {
			const response = await ApiService.customRequest(requestData);
			TokenService.saveToken(response.data.token);
			ApiService.setHeader();
			ApiService.mount401Interceptor();
			return response;
		} catch (error) {
			throw new AuthenticationError(error.data.status, error.data.message);
		}
	},
	/**
	 * Logout the current user by removing the token from storage.
	 *
	 * Will also remove `Authorization Bearer <token>` header from future requests.
	 **/
	logout(){
		// Remove the token and remove Authorization header from Api Service as well
		ApiService.post('/api/logout');
		TokenService.removeToken();
		TokenService.removeGuestInfo()
		TokenService.removeCurrentUser()
		ApiService.removeHeader();
		// // NOTE: Again, we'll cover the 401 Interceptor a bit later.
		ApiService.unmount401Interceptor();
	},
	profileUser(){
		return ApiService.get(`/api/profile`);
	},
	profileUpdate(data){
		return ApiService.post(`/api/user/update`,data);
	},
	changePass(data){
		return ApiService.post(`/api/change-password`,data);
	}
};

export { UserService,AuthenticationError };
