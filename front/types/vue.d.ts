import { Vue } from 'vue/types/vue'
import { ApolloClient } from 'apollo-client'

declare module 'vue/types/vue' {
  export interface Vue {
    $defaultApolloClient: ApolloClient<any>
    tttt: any
  }
}
