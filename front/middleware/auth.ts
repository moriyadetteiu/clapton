import { Context } from '@nuxt/types'
import { MeQuery } from '~/apollo/graphql'
import { userStore } from '~/store'

// 未ログインでもアクセスできるルート
const CAN_ACCESS_GUEST_ROUTE_NAMES = ['login', 'users-create']

const canAccessGuest = (routeName: string): boolean => {
  return CAN_ACCESS_GUEST_ROUTE_NAMES.includes(routeName)
}

export default async ({
  app,
  route,
  $config,
  $axios,
}: Context & { $axios: any }) => {
  // csrf tokenの更新を行う
  // note: csrf tokenはcookieに書き込まれる（ブラウザで処理される）ため、レスポンスの処理はせずにリクエストするだけでOK
  await $axios.get('sanctum/csrf-cookie')

  const isUseMock = $config.IS_USE_MOCK_SERVER
  const routeName = route.name ?? ''
  if (!isUseMock && !canAccessGuest(routeName)) {
    // note: ログインできていない場合、ここからapolloErrorLinkのUnauthenticatedのログアウト / リダイレクト処理が走る
    const user = await app.$defaultApolloClient
      .query({ query: MeQuery })
      .then((result: any) => result.data.me)
    userStore.setLoginUser(user)
  }
}
