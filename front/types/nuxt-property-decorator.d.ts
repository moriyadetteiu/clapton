import { VuexModule } from 'nuxt-property-decorator'
import { Store } from 'vuex'

declare module 'nuxt-property-decorator' {
  export interface VuexModule {
    store: Store<any>
  }
}
