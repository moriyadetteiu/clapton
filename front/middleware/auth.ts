import { Context } from '@nuxt/types';

// 未ログインでもアクセスできるルート
const CAN_ACCESS_GUEST_ROUTE_NAMES = [
  'login',
  'users-create'
];

const canAccessGuest = (routeName: string): boolean => {
  return CAN_ACCESS_GUEST_ROUTE_NAMES.includes(routeName);
}

export default ({ app, redirect, route, $config }: Context) => {
  const isUseMock = $config.IS_USE_MOCK_SERVER;
  const routeName = route.name ?? '';
  const token = app.$apolloHelpers.getToken();
  if (!isUseMock && !canAccessGuest(routeName) && !token) {
    return redirect('/login');
  }
};
