import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import UserForm from '~/components/users/UserForm.vue'
import { UserInput } from '~/apollo/graphql'

const ERROR_MESSAGE_SELECTOR: string = '.v-messages.error--text'

describe('UserForm test', () => {
  test('require validation error test', async () => {
    const userInput: UserInput = {
      name: '',
      name_kana: '',
      handle_name: '',
      handle_name_kana: '',
      email: '',
      password: '',
    }

    const wrapper = mount(UserForm, {
      propsData: {
        user: userInput,
      },
    })

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeFalsy()

    const errorMessages = wrapper
      .findAll(ERROR_MESSAGE_SELECTOR)
      .wrappers.map((value) => value.text())
      .join('')

    const expectErrorLabels = [
      '名前',
      'かな',
      'ハンドルネーム',
      'ハンドルネームのかな',
      'メールアドレス',
      'パスワード',
    ]

    expectErrorLabels.forEach((label) => {
      expect(errorMessages).toContain(label)
    })
  })

  test('email validation error test', async () => {
    const userInput: UserInput = {
      name: 'test',
      name_kana: 'test',
      handle_name: 'test',
      handle_name_kana: 'test',
      email: 'dummy',
      password: 'test',
    }

    const wrapper = mount(UserForm, {
      propsData: {
        user: userInput,
      },
    })

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeFalsy()

    const errorMessages = wrapper
      .findAll(ERROR_MESSAGE_SELECTOR)
      .wrappers.map((value) => value.text())
      .join('')

    expect(errorMessages).toContain('メールアドレス')
  })

  test('succeed submit test', async () => {
    const userInput: UserInput = {
      name: 'test',
      name_kana: 'test',
      handle_name: 'test',
      handle_name_kana: 'test',
      email: 'test@test.test',
      password: 'test',
    }

    const wrapper = mount(UserForm, {
      propsData: {
        user: userInput,
      },
    })
    wrapper.setData({
      confirmationPassword: 'test',
    })
    await flushPromises()

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeTruthy()
    const errorMessages = wrapper.findAll(ERROR_MESSAGE_SELECTOR).wrappers

    expect(errorMessages).toHaveLength(0)
  })
})
