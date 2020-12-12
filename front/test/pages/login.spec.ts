import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import Login from '~/pages/login.vue'
import { LoginInput } from '~/apollo/graphql'

describe('login page', () => {
  test('success login test', async () => {
    const token: string = 'testtoken'

    const mutate = jest.fn((option) => ({
      data: {
        login: {
          token,
        },
      },
    }))
    const onLogin = jest.fn()
    const push = jest.fn()
    const success = jest.fn()

    const wrapper = mount(Login, {
      mocks: {
        $apollo: {
          mutate,
        },
        $apolloHelpers: {
          onLogin,
        },
        $router: {
          push,
        },
        $toast: {
          success,
        },
      },
      stubs: ['nuxt-link'],
    })

    const credential: LoginInput = {
      email: 'test@test.test',
      password: 'test',
    }
    await wrapper.setData({
      credential,
    })

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(mutate).toBeCalled()
    const loginInputs: LoginInput = mutate.mock.calls[0][0].variables.input
    expect(loginInputs.email).toBe(credential.email)
    expect(loginInputs.password).toBe(credential.password)
    expect(onLogin).toBeCalled()
    expect(onLogin.mock.calls[0][0]).toBe(token)
    expect(push).toBeCalled()
    expect(push.mock.calls[0][0]).toBe('/')
    expect(success).toBeCalled()
  })
})
