import { Context } from '@nuxt/types'
import { MeQuery } from '~/apollo/graphql'
import { userStore } from '~/store'

// 未ログインでもアクセスできるルート
const CAN_ACCESS_GUEST_ROUTE_NAMES = [
  'login',
  'users-create',
  'password-forget',
  'password-reset',
]

const canAccessGuest = (routeName: string): boolean => {
  return CAN_ACCESS_GUEST_ROUTE_NAMES.includes(routeName)
}

export default async ({
  app,
  route,
  $config,
  $axios,
  redirect,
}: Context & { $axios: any }) => {
  // csrf tokenの更新を行う
  // note: csrf tokenはcookieに書き込まれる（ブラウザで処理される）ため、レスポンスの処理はせずにリクエストするだけでOK
  await $axios.get('sanctum/csrf-cookie')

  const isUseMock = $config.IS_USE_MOCK_SERVER
  const routeName = route.name ?? ''
  if (!isUseMock && !canAccessGuest(routeName)) {
    try {
      const user = await app.$defaultApolloClient
        .query({ query: MeQuery, fetchPolicy: 'no-cache' })
        .then((result: any) => result.data.me)
      userStore.setLoginUser(user)
    } catch (e) {
      // note: 明示的にredirect関数を呼び出し、返さないと一瞬遷移先のページが表示されてしまうので、これは必要
      return redirect('/login')
    }
  }
}
