import 'vuex'
import { ApolloClient } from 'apollo-client'
import { ConfirmDialogInterface } from '~/components/dialog/ConfirmDialog/ConfirmDialogContainer'

declare module 'vuex' {
  interface Store<S> {
    $defaultApolloClient: ApolloClient<any>
    $confirmDialog: ConfirmDialogInterface
  }
}
