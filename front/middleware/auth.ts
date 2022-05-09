import { Context } from '@nuxt/types'
import { MeQuery } from '~/apollo/graphql'

// 未ログインでもアクセスできるルート
const CAN_ACCESS_GUEST_ROUTE_NAMES = ['login', 'users-create']

const canAccessGuest = (routeName: string): boolean => {
  return CAN_ACCESS_GUEST_ROUTE_NAMES.includes(routeName)
}

export default async ({ app, redirect, route, $config }: Context) => {
  const isUseMock = $config.IS_USE_MOCK_SERVER
  const routeName = route.name ?? ''
  const token = app.$apolloHelpers.getToken()
  if (!isUseMock && !canAccessGuest(routeName) && !token) {
    return redirect('/login')
  }

  // tokenが有効か確認し、認証が通らなかった場合はログアウトさせる
  if (token) {
    try {
      await app.$defaultApolloClient.query({ query: MeQuery })
    } catch (e: any) {
      if (~e.message.indexOf('Unauthenticated')) {
        app.$apolloHelpers.onLogout()
      }
    }
  }
}
