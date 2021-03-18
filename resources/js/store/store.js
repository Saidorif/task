import Vue from 'vue'
import Vuex from 'vuex'

// ability
import storage from "./storage"
import {abilityPlugin, ability as appAbility} from './ability'
import router from "./../routes"

// user service
import {UserService} from "../services/user.service";
// modules
import {user} from "./user.module"
import {role} from "./role.module"
import {conts} from "./conts.module"
import {action} from "./action.module"
import {task} from "./task.module"
import {region} from "./region.module"
import {area} from "./area.module"
import {usertask} from "./usertask.module"
import {dashboard} from "./dashboard.module"

import { TokenService } from './../services/storage.service'

Vue.use(Vuex)
export const ability = appAbility

const store = new Vuex.Store(
	{
		plugins:[
			storage({
				storedKeys:['token','rules'],
				destroyOn:['logoutSuccess']
			}),
			abilityPlugin
		],
		modules:{
			user,
			role,
			conts,
			action,
            task,
			dashboard,
            region,
            area,
            usertask
		},
		state:{
			rules:[],
			userInfo:[],
			token:'',
			authenticationErrorCode: false,
			authenticationError: '',
			registerError: '',
			authenticating: false,
			registerErrorCode: false,
		},
		getters:{
			getUser(state){
				return state.userInfo
			},
			authenticationErrorCode(state){
				return state.authenticationErrorCode
			},

			authenticationError(state){
				return state.authenticationError
			},
			registerError(state){
				return state.registerErrorCode
			},
			authenticating(state){
				return state.authenticating
			}
		},
		mutations:{
			loginRequest(state){
				state.authenticating = true;
				state.authenticationError = '';
				state.authenticationErrorCode = false
			},
			logoutSuccess(state){
				state.token = '';
				state.rules = [];
			},
			loginSuccess(state,user){
				TokenService.saveCurrentUser(user.result.user);
				state.token = user.token;
				state.authenticationErrorCode = false
				state.userInfo = user.result.user;
				state.rules = user.result.permissions;
			},
			loginError(state,user){
				state.authenticating = false
				state.authenticationErrorCode = user.data.error
				state.authenticationError = user.data.message
			}
		},
		actions:{
			async login({ commit,dispatch }, userData){
				try{
					const user = await UserService.login(userData);
					if(user.data.error === true){
						await commit('loginError', user);
					}else{
						await dispatch('profileUser');
						// let routeHistory = router.history.current.query.redirect
						// router.push(routeHistory && routeHistory != '/' ? routeHistory : '/')
					}
				}catch(e){}
			},
			async profileUser({commit}){
				try{
					const userInfo = await UserService.profileUser();
					await commit('loginSuccess', userInfo.data);
					return true
				}catch(e){
					return false
				}
			},
			// register
			async register({ commit,dispatch }, payload){
				const newUser = await UserService.register(payload);
				if(newUser.data.error === true){
					await commit('registerError', newUser);
				}
			},
			logout({ commit }){
				UserService.logout();
				commit('logoutSuccess');
				router.push('/');
			},
		}
	}
)

export default store;
