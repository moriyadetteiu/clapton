// docs: https://github.com/championswimmer/vuex-module-decorators#accessing-modules-with-nuxtjs
import { Store } from 'vuex'
import { getModule } from 'vuex-module-decorators'
import UserModule from '~/store/user'
import FavoriteModule from '~/store/favorite'

let userStore: UserModule
let favoriteStore: FavoriteModule

function initializeStores(store: Store<any>): void {
  userStore = getModule(UserModule, store)
  favoriteStore = getModule(FavoriteModule, store)
}

export { initializeStores, userStore, favoriteStore }
