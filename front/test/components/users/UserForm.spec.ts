import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import UserForm from '~/components/users/UserForm.vue'
import { UserInput } from '~/apollo/graphql'
import { findAllErrorMessages } from '~/test/utils/wrapper-helpers'
import UserInputFactory from '~/test/factory/UserInputFactory'
import { CreateUserInputValidation } from '~/validation/validations'

const makeWrapper = (userInput: UserInput) => {
  const onSubmit = jest.fn(() => new Promise(() => {}))
  const wrapper = mount(UserForm, {
    propsData: {
      user: userInput,
      onSubmit,
    },
  })

  return { wrapper, onSubmit }
}

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
      const { wrapper, onSubmit } = makeWrapper(userInput)

      wrapper.find('.v-btn.success').trigger('submit')
      await flushPromises()

      expect(onSubmit.mock.calls.length).toBe(0)

      const errorMessages = findAllErrorMessages(wrapper).join('')
      expect(errorMessages).toContain(label)
    })
  })

  test('email validation error test', async () => {
    const userInput: UserInput = new UserInputFactory().make({
      email: 'invalid-address',
    })

    const { wrapper, onSubmit } = makeWrapper(userInput)

    wrapper.find('.v-btn.success').trigger('submit')
    await flushPromises()

    expect(onSubmit.mock.calls.length).toBe(0)

    const errorMessages = findAllErrorMessages(wrapper).join('')

    expect(errorMessages).toContain('メールアドレス')
  })

  test('confirmation password invalid test', async () => {
    const userInput: UserInput = new UserInputFactory().make()

    const { wrapper, onSubmit } = makeWrapper(userInput)
    wrapper.setData({
      confirmationPassword: 'dummy',
    })
    await flushPromises()

    wrapper.find('.v-btn.success').trigger('submit')
    await flushPromises()

    expect(onSubmit.mock.calls.length).toBe(0)
    const errorMessages = findAllErrorMessages(wrapper).join('')

    expect(errorMessages).toContain('パスワード')
  })

  test('succeed submit test', async () => {
    const userInput: UserInput = new UserInputFactory().make()

    const { wrapper, onSubmit } = makeWrapper(userInput)
    wrapper.setData({
      confirmationPassword: userInput.password,
    })
    await flushPromises()

    wrapper.find('.v-btn.success').trigger('submit')
    await flushPromises()

    expect(onSubmit).toBeCalled()
    const errorMessages = findAllErrorMessages(wrapper)

    expect(errorMessages).toHaveLength(0)
  })
})
