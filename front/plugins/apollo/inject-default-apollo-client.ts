import { Context } from '@nuxt/types'
import { Inject } from '@nuxt/types/app'

/**
 *  vuex storeからapolloの呼び出しをできるようにするために、defaultClientをinjectしている
 *  vuex store内では this.store.$defaultApolloClient.query(...) のように呼び出せるようになる
 *
 *  該当のプロパティの型はtypes/vuex.d.ts等に記述してある
 */
export default ({ app }: Context, inject: Inject) => {
  inject('defaultApolloClient', app.apolloProvider!.defaultClient)
}
