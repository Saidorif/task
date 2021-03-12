import { Ability } from '@casl/ability'

export const ability = new Ability()

export const abilityPlugin = (store) => {
	
	ability.update(store.state.rules);
	return store.subscribe((mutation) => {
		switch (mutation.type){
			case 'loginSuccess':
				ability.update(mutation.payload.result.permissions);
				break
			case 'logoutSuccess':
				ability.update([{ actions: 'read', subject: 'all' }])
				break
		}
	})
}
