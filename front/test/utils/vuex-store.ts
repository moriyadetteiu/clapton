import Vuex, { Store } from 'vuex'
import { initializeStores } from '~/store'
import UserStoreModule from '~/store/user'

let store: Store<any>

// note: beforeEachで毎回呼び出すことで、テストごとにvuexのstoreをresetできる
// note: storeの数が増えるようであれば、登録するmodulesを引数で受け取る感じにしてもいいかも。
//       また、mockしたstoreを受け取れるようにするのもいいかもしれない。
const resetStore = () => {
  store = new Vuex.Store({
    modules: {
      user: UserStoreModule,
    },
  })
  initializeStores(store)
}

// note: mountにstoreを渡すことで、mountしたvueインスタンスからvuexのstoreが参照できるようになる
export { resetStore, store }
export * from '~/store'
