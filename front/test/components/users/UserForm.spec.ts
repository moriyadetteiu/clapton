import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import UserForm from '~/components/users/UserForm.vue'
import { UserInput } from '~/apollo/graphql'
import { findAllErrorMessages } from '~/test/utils/wrapper-helpers'
import UserInputFactory from '~/test/factory/UserInputFactory'
import { CreateUserInputValidation } from '~/validation/validations'

describe('UserForm test', () => {
  test('require validation error test', async () => {
    const validation = new CreateUserInputValidation()
    let requireFields: Partial<UserInput> = {}
    Object.entries(validation.getItems())
      .filter((item) => item[1].rules!.indexOf('required'))
      .forEach((item) => {
        const [name, definition] = item
        const PartialInput: Partial<UserInput> = {
          [name]: definition.attribute,
        }
        requireFields = {
          ...requireFields,
          ...PartialInput,
        }
      })

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
