import { Context } from '@nuxt/types'
import FileDownloader from '~/utils/FileDownloader'
import { confirmDialogContainer } from '~/components/dialog/ConfirmDialog/ConfirmDialogContainer'

export default ({ $config }: Context) => {
  FileDownloader.setBaseUrl($config.BROWSER_HTTP_AXIOS_ENDPOINT)
}
