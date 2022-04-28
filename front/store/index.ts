// docs: https://github.com/championswimmer/vuex-module-decorators#accessing-modules-with-nuxtjs
// note: vuexではなく、apolloのlocalStateを使う方法もあるが、オブジェクトを保持したい場合に、
//       Mutationの引数のinputを毎回しているする必要がある（＋tsファイルからimportできない）ことが不便なので、
//       本プロジェクトではvuexを使っている。（いい方法があれば、変わるかも）
import { Store } from 'vuex'
import { initializeStores } from '~/utils/store-accessor'
const initializer = (store: Store<any>) => initializeStores(store)
export const plugins = [initializer]
export * from '~/utils/store-accessor'
