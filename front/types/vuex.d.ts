import 'vuex'
import { ApolloClient } from 'apollo-client'

declare module 'vuex' {
  interface Store<S> {
    $defaultApolloClient: ApolloClient<any>
  }
}
