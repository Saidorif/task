import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import {TokenService} from './services/storage.service'
import {ability} from "./store/store";
import store from "./store/store";

// Dashboard component
import Dashboard from './components/admin/Dashboard'
import Admin from './components/admin/layouts/Admin'
import Login from './components/auth/login'
import Profile from './components/admin/user/Profile'

// Role
import Role from './components/admin/role/Role'
import AddRole from './components/admin/role/AddRole'
import EditRole from './components/admin/role/EditRole'
import Permission from './components/admin/permission/Permission'

// controller
import Conts from './components/admin/conts/Conts'
import AddConts from './components/admin/conts/AddConts'
import EditConts from './components/admin/conts/EditConts'

// action
import Action from './components/admin/action/Action'
import AddAction from './components/admin/action/AddAction'
import EditAction from './components/admin/action/EditAction'

// Tasks
import Tasks from './components/admin/tasks/Tasks'
import AddTask from './components/admin/tasks/AddTask'
import EditTask from './components/admin/tasks/EditTask'

// Usertask
import UserTasks from './components/admin/usertask/UserTasks'
import AddUserTask from './components/admin/usertask/AddUserTask'
import EditUserTask from './components/admin/usertask/EditUserTask'

// Region
import Region from './components/admin/region/Region'
import AddRegion from './components/admin/region/AddRegion'
import EditRegion from './components/admin/region/EditRegion'

// Area
import Area from './components/admin/area/Area'
import AddArea from './components/admin/area/AddArea'
import EditArea from './components/admin/area/EditArea'

// Users
import Users from './components/admin/users/Users'

// NotFound
import NotFound from './components/NotFound/NotFound'
const router = new Router({
	mode: 'history',
	base: process.env.BASE_URL,
	linkActiveClass: 'active',
	routes: [
		// public components
		{
			path:'/crm',
			name:'crm',
			redirect:'/crm/dashboard',
			meta:{
				requiredAuth:true
			},
			component:Admin,
			children:[
				{
					path:'dashboard',
					component:Dashboard,
					// meta:{
					// 	action:'index',
					// 	subject:'IndexController'
					// }
				},
                {
					path:'profile',
					component:Profile,
				},
				{
					path:'users',
					component:Users,
				},
				{
					path:'tasks',
					component:Tasks,
				},
				{
					path:'tasks/add',
					component:AddTask,
				},
				{
					path:'tasks/edit/:taskId',
					component:EditTask,
				},
				{
					path:'user-task',
					component:UserTasks,
				},
				{
					path:'user-task/add',
					component:AddUserTask,
				},
				{
					path:'user-task/edit/:taskId',
					component:EditUserTask,
				},
				{
					path:'region',
					component:Region,
				},
				{
					path:'region/add',
					component:AddRegion,
				},
				{
					path:'region/edit/:regionId',
					component:EditRegion,
				},
                {
					path:'area',
					component:Area,
				},
				{
					path:'area/add',
					component:AddArea,
				},
				{
					path:'area/edit/:areaId',
					component:EditArea,
				},
				{
					path:'role',
					component:Role,
				},
				{
					path:'role/add',
					component:AddRole,
				},
				{
					path:'role/edit/:roleId',
					component:EditRole,
				},
				{
					path:'role/:roleId',
					component:Permission,
				},
				{
					path:'conts',
					component:Conts,
				},
				{
					path:'conts/add',
					component:AddConts,
				},
				{
					path:'conts/edit/:contId',
					component:EditConts,
				},
				{
					path:'action',
					component:Action,
				},
				{
					path:'action/add',
					component:AddAction,
				},
				{
					path:'action/edit/:actionId',
					component:EditAction,
				}
			]
		},
		{
			path:'/',
			name:'login',
			components:{
				default:Login,
			},
		},
		{
			path:'*',
			name:'notfound',
			components:{
				default:NotFound,
			},
		}
	]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiredAuth)) {
  	const loggedIn = TokenService.getToken();
    if (!loggedIn || loggedIn == 'undefined'){
      next({
        path: '/',
        query: { redirect: to.fullPath }
      })
    } else {
      	next()
    }
  } else {
    next()
  }
})

export default router;
