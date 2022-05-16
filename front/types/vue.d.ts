import { Vue } from 'vue/types/vue'
import { ApolloClient } from 'apollo-client'
import { ConfirmDialogInterface } from '~/components/dialog/ConfirmDialog/ConfirmDialogContainer'

declare module 'vue/types/vue' {
  export interface Vue {
    $defaultApolloClient: ApolloClient<any>
    $confirmDialog: ConfirmDialogInterface
  }
}
