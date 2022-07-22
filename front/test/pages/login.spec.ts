import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import Login from '~/pages/login.vue'
import { LoginInput } from '~/apollo/graphql'
import UserFactory from '~/test/factory/UserFactory'
import { User } from 'apollo/graphql'
import { resetStore, store, userStore } from '~/test/utils/vuex-store'

describe('login page', () => {
  beforeEach(() => {
    resetStore()
  })

  test('success login test', async () => {
    const token: string = 'testtoken'
    const mutate = jest.fn((option) => ({
      data: {
        login: {
          token,
        },
      },
    }))

    const user: User = new UserFactory().make()
    const query = jest.fn(async () => ({
      data: {
        me: user,
      },
    }))

    const push = jest.fn()
    const success = jest.fn()

    const wrapper = mount(Login, {
      store,
      mocks: {
        $apollo: {
          mutate,
          query,
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

    wrapper.find('.v-btn.success').trigger('submit')
    await flushPromises()

    expect(mutate).toBeCalled()
    const loginInputs: LoginInput = mutate.mock.calls[0][0].variables.input
    expect(loginInputs.email).toBe(credential.email)
    expect(loginInputs.password).toBe(credential.password)
    expect(push).toBeCalled()
    expect(push.mock.calls[0][0]).toBe('/mypage')
    expect(success).toBeCalled()
    expect(userStore.loginUser?.id || null).toBe(user.id)
  })
})
