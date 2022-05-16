import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import Factory from '~/test/factory/Factory'
import DataTableStub from '~/test/stubs/DataTable.vue'
import VDialogStub from '~/test/stubs/VDialog.vue'
import { VueConstructor } from 'vue'

const makeWrapper = (
  factory: Factory<any>,
  pageComponent: VueConstructor<Vue>
) => {
  const teamId: string = '1234567890'
  const mutate = jest.fn((value) => {
    return new Promise<void>((resolve) => {
      resolve()
    })
  })
  const refetch = jest.fn()
  const confirm = jest.fn((value) => {
    return new Promise((resolve) => {
      resolve(true)
    })
  })
  const wrapper = mount(pageComponent, {
    mocks: {
      $route: {
        params: {
          team_id: teamId,
        },
      },
      $apollo: {
        mutate,
        queries: {
          models: {
            refetch,
          },
        },
      },
      $toast: {
        success: jest.fn(),
        error: jest.fn(),
      },
      $confirmDialog: {
        confirm,
      },
    },
    stubs: {
      'v-data-table': DataTableStub,
      'v-dialog': VDialogStub,
    },
  })

  const models = [...new Array(3)].map(() => {
    return factory.make()
  })

  wrapper.setData({
    models,
  })

  return {
    wrapper,
    mutate,
    refetch,
    models,
  }
}

export const testMasterPage = (
  factory: Factory<any>,
  pageComponent: VueConstructor<Vue>,
  tableItemNames: string[]
) => {
  test('see table', async () => {
    const { wrapper, models } = makeWrapper(factory, pageComponent)
    await flushPromises()

    models.forEach((model) => {
      tableItemNames.forEach((itemName) => {
        expect(wrapper.text()).toContain(model[itemName])
      })
    })
  })

  test('open form test', async () => {
    const { wrapper } = makeWrapper(factory, pageComponent)
    await flushPromises()

    const vm: any = wrapper.vm as any
    expect(wrapper.findAll('.v-dialog').length).toBe(0)
    vm.create()
    await flushPromises()
    expect(wrapper.findAll('.v-dialog').length).toBe(1)
  })

  test('remove test', async () => {
    const { wrapper, mutate, refetch, models } = makeWrapper(
      factory,
      pageComponent
    )
    const model = models[0]
    await flushPromises()

    const vm: any = wrapper.vm as any
    vm.remove(model)
    await flushPromises()
    expect(mutate).toBeCalled()
    expect(mutate.mock.calls[0][0].variables.id).toBe(model.id)
    expect(refetch).toBeCalled()
    expect(wrapper.findAll('.v-dialog').length).toBe(0)
  })
}
