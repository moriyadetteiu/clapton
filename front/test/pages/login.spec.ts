import { mount } from '@vue/test-utils'
import Login from '~/pages/login.vue';

describe('login page', () => {
  test('success login test', async () => {
    const token = 'testtoken';

    const mutate = jest.fn((option) => ({
      data: {
        login: {
          token,
        }
      },
    }));
    const onLogin = jest.fn();
    const push = jest.fn();

    const wrapper = mount(Login, {
      mocks: {
        $apollo: {
          mutate,
        },
        $apolloHelpers: {
          onLogin
        },
        $router: {
          push
        }
      },
      stubs: ['nuxt-link'],
    });

    const credential = {
      email: 'test@test.test',
      password: 'test',
    };
    await wrapper.setData({
      credential,
    });

    await (wrapper.vm as any).login();

    expect(mutate).toBeCalled()
    const loginInputs = mutate.mock.calls[0][0].variables.input;
    expect(loginInputs.email).toBe(credential.email);
    expect(loginInputs.password).toBe(credential.password);
    expect(onLogin).toBeCalled()
    expect(onLogin.mock.calls[0][0]).toBe(token);
    expect(push).toBeCalled()
    expect(push.mock.calls[0][0]).toBe('/');
  })
})
