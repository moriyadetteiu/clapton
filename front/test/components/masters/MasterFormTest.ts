import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import Factory from '~/test/factory/Factory'
import VDialogStub from '~/test/stubs/VDialog.vue'
import { findAllErrorMessages } from '~/test/utils/wrapper-helpers'
import { VueConstructor } from 'vue'

const makeWrapper = (
  factory: Factory<any>,
  formComponent: VueConstructor<Vue>
) => {
  const mutate = jest.fn((value) => {
    return new Promise<void>((resolve) => {
      resolve()
    })
  })

  const model = factory.make()

  const wrapper = mount(formComponent, {
    propsData: {
      model,
      isOpen: true,
      teamId: model.id,
    },
    mocks: {
      $apollo: {
        mutate,
      },
      $toast: {
        success: jest.fn(),
      },
    },
    stubs: {
      'v-dialog': VDialogStub,
    },
  })

  wrapper.setData({
    model,
  })

  return {
    wrapper,
    mutate,
    model,
  }
}

export const testMasterForm = (
  factory: Factory<any>,
  formComponent: VueConstructor<Vue>,
  emptyInput: Object
) => {
  test('validation check', async () => {
    const { wrapper } = makeWrapper(factory, formComponent)
    wrapper.setData({
      input: emptyInput,
    })
    await flushPromises()

    wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(findAllErrorMessages(wrapper).length).toBeGreaterThan(0)
  })

  test('succeed edit submit test', async () => {
    const { wrapper, mutate } = makeWrapper(factory, formComponent)
    await flushPromises()
    wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(mutate).toBeCalled()
    const mutateArgs = mutate.mock.calls[0][0]
    expect('id' in mutateArgs.variables).toBeTruthy()
  })

  test('succeed create submit test', async () => {
    const { wrapper, mutate, model } = makeWrapper(factory, formComponent)
    await flushPromises()
    wrapper.setProps({
      model: {
        ...model,
        id: '',
      },
    })
    wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(mutate).toBeCalled()
    const mutateArgs = mutate.mock.calls[0][0]
    expect('id' in mutateArgs.variables).toBeFalsy()
  })
}
