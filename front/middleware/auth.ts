import { Context } from '@nuxt/types';

// 未ログインでもアクセスできるルート
const CAN_ACCESS_GUEST_ROUTE_NAMES = [
  'login',
  'users-create'
];

const canAccessGuest = (routeName: string): boolean => {
  return CAN_ACCESS_GUEST_ROUTE_NAMES.includes(routeName);
}

export default ({ app, redirect, route }: Context) => {
  const routeName = route.name ?? '';
  const token = app.$apolloHelpers.getToken();
  if (!canAccessGuest(routeName) && !token) {
    return redirect('/login');
  }
};
