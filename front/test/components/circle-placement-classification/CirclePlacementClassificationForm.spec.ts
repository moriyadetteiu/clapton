import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import CirclePlacementClassificationForm from '~/components/circle-placement-classification/CirclePlacementClassificationForm.vue'
import DataTableStub from '~/test/stubs/DataTable.vue'
import CirclePlacementClassificationFactory from '~/test/factory/CirclePlacementClassificationFactory'
import VDialogStub from '~/test/stubs/VDialog.vue'
import { findAllErrorMessages } from '~/test/utils/wrapper-helpers'

const makeWrapper = () => {
  const mutate = jest.fn((value) => {
    return new Promise<void>((resolve) => {
      resolve()
    })
  })

  const factory = new CirclePlacementClassificationFactory()
  const circlePlacementClassification = factory.make()

  const wrapper = mount(CirclePlacementClassificationForm, {
    propsData: {
      circlePlacementClassification,
      isOpen: true,
      teamId: circlePlacementClassification.id,
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
    circlePlacementClassification,
  })

  return {
    wrapper,
    mutate,
    circlePlacementClassification,
  }
}

describe('circle placement classification form', () => {
  test('validation check', async () => {
    const { wrapper } = makeWrapper()
    wrapper.setData({
      input: {
        team_id: '',
        name: '',
        cost: 0,
      },
    })
    await flushPromises()

    wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(findAllErrorMessages(wrapper).length).toBeGreaterThan(0)
  })

  test('succeed edit submit test', async () => {
    const { wrapper, mutate } = makeWrapper()
    await flushPromises()
    wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(mutate).toBeCalled()
    const mutateArgs = mutate.mock.calls[0][0]
    expect('id' in mutateArgs.variables).toBeTruthy()
  })

  test('succeed create submit test', async () => {
    const { wrapper, mutate, circlePlacementClassification } = makeWrapper()
    await flushPromises()
    wrapper.setProps({
      circlePlacementClassification: {
        ...circlePlacementClassification,
        id: '',
      },
    })
    wrapper.find('form').trigger('submit')
    await flushPromises()
    expect(mutate).toBeCalled()
    const mutateArgs = mutate.mock.calls[0][0]
    expect('id' in mutateArgs.variables).toBeFalsy()
  })
})
