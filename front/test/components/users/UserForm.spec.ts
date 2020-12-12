import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import UserForm from '~/components/users/UserForm.vue'
import { UserInput } from '~/apollo/graphql'
import { findAllErrorMessages } from '~/test/utils/wrapper-helpers'
import UserInputFactory from '~/test/factory/UserInputFactory'

describe('UserForm test', () => {
  test('require validation error test', async () => {
    const requireFields: Partial<UserInput> = {
      name: '名前',
      name_kana: 'かな',
      handle_name: 'ハンドルネーム',
      handle_name_kana: 'ハンドルネームのかな',
      email: 'メールアドレス',
      password: 'パスワード',
    }

    await Object.entries(requireFields).forEach(async (field) => {
      const [name, label] = field
      const userInput: UserInput = new UserInputFactory().make({ [name]: '' })
      const wrapper = mount(UserForm, {
        propsData: {
          user: userInput,
        },
      })

      wrapper.find('.v-btn.success').trigger('click')
      await flushPromises()

      expect(wrapper.emitted().submit).toBeFalsy()

      const errorMessages = findAllErrorMessages(wrapper).join('')
      expect(errorMessages).toContain(label)
    })
  })

  test('email validation error test', async () => {
    const userInput: UserInput = new UserInputFactory().make({
      email: 'invalid-address',
    })

    const wrapper = mount(UserForm, {
      propsData: {
        user: userInput,
      },
    })

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeFalsy()

    const errorMessages = findAllErrorMessages(wrapper).join('')

    expect(errorMessages).toContain('メールアドレス')
  })

  test('confirmation password invalid test', async () => {
    const userInput: UserInput = new UserInputFactory().make()

    const wrapper = mount(UserForm, {
      propsData: {
        user: userInput,
      },
    })
    wrapper.setData({
      confirmationPassword: 'dummy',
    })
    await flushPromises()

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeFalsy()
    const errorMessages = findAllErrorMessages(wrapper).join('')

    expect(errorMessages).toContain('パスワード')
  })

  test('succeed submit test', async () => {
    const userInput: UserInput = new UserInputFactory().make()

    const wrapper = mount(UserForm, {
      propsData: {
        user: userInput,
      },
    })
    wrapper.setData({
      confirmationPassword: userInput.password,
    })
    await flushPromises()

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeTruthy()
    const errorMessages = findAllErrorMessages(wrapper)

    expect(errorMessages).toHaveLength(0)
  })
})
