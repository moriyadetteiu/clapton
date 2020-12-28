import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import CirclePlacementClassification from '~/pages/teams/_team_id/circle-placement-classifications.vue'
import DataTableStub from '~/test/stubs/DataTable.vue'
import CirclePlacementClassificationFactory from '~/test/factory/CirclePlacementClassificationFactory'
import VDialogStub from '~/test/stubs/VDialog.vue'

const makeWrapper = () => {
  const teamId: string = '1234567890'
  const mutate = jest.fn((value) => {
    return new Promise<void>((resolve) => {
      resolve()
    })
  })
  const refetch = jest.fn()
  const wrapper = mount(CirclePlacementClassification, {
    mocks: {
      $route: {
        params: {
          team_id: teamId,
        },
      },
      $apollo: {
        mutate,
        queries: {
          circlePlacementClassifications: {
            refetch,
          },
        },
      },
      $toast: {
        success: jest.fn(),
        error: jest.fn(),
      },
    },
    stubs: {
      'v-data-table': DataTableStub,
      'v-dialog': VDialogStub,
    },
  })

  const factory = new CirclePlacementClassificationFactory()
  const circlePlacementClassifications = [...new Array(3)].map(() => {
    return factory.make()
  })

  wrapper.setData({
    circlePlacementClassifications,
  })

  return {
    wrapper,
    mutate,
    refetch,
    circlePlacementClassifications,
  }
}

describe('circle placement classification page', () => {
  test('see table', async () => {
    const { wrapper, circlePlacementClassifications } = makeWrapper()
    await flushPromises()

    circlePlacementClassifications.forEach((circlePlacementClassification) => {
      expect(wrapper.text()).toContain(circlePlacementClassification.name)
      expect(wrapper.text()).toContain(circlePlacementClassification.cost)
    })
  })

  test('open form test', async () => {
    const { wrapper } = makeWrapper()
    await flushPromises()

    const vm: any = wrapper.vm as any
    expect(wrapper.findAll('.v-dialog').length).toBe(0)
    vm.create()
    await flushPromises()
    expect(wrapper.findAll('.v-dialog').length).toBe(1)
  })

  test('remove test', async () => {
    const {
      wrapper,
      mutate,
      refetch,
      circlePlacementClassifications,
    } = makeWrapper()
    const circlePlacementClassification = circlePlacementClassifications[0]
    await flushPromises()

    const vm: any = wrapper.vm as any
    expect(wrapper.findAll('.v-dialog').length).toBe(0)

    vm.confirmRemove(circlePlacementClassification)
    await flushPromises()
    expect(wrapper.findAll('.v-dialog').length).toBe(1)

    vm.remove()
    await flushPromises()
    expect(mutate).toBeCalled()
    expect(mutate.mock.calls[0][0].variables.id).toBe(
      circlePlacementClassification.id
    )
    expect(refetch).toBeCalled()
    expect(wrapper.findAll('.v-dialog').length).toBe(0)
  })
})
