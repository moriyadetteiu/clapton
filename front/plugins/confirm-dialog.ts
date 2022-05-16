import { Context } from '@nuxt/types'
import { Inject } from '@nuxt/types/app'
import { confirmDialogContainer } from '~/components/dialog/ConfirmDialog/ConfirmDialogContainer'

export default ({ app }: Context, inject: Inject) => {
  inject('confirmDialog', confirmDialogContainer)
}
