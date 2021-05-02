import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import Vuetify from 'vuetify'
import EventCreatePage from '~/pages/teams/_team_id/events/create.vue'
import TeamFactory from '~/test/factory/TeamFactory'

describe('my page index', () => {
  let vuetify: Vuetify

  beforeEach(() => {
    vuetify = new Vuetify()
  })

  test('see contents', async () => {
    const team = new TeamFactory().make()
    const mutate = jest.fn((option) => {
      return new Promise<void>((resolve) => {
        resolve()
      })
    })
    const push = jest.fn()
    const success = jest.fn()

    const wrapper = mount(EventCreatePage, {
      vuetify,
      mocks: {
        $route: {
          params: {
            team_id: team.id,
          },
        },
        $apollo: {
          mutate,
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

    await flushPromises()

    expect((wrapper.vm as any).eventDates.length).toBe(1)

    wrapper.find('.v-btn.primary').trigger('click')
    await flushPromises()

    expect((wrapper.vm as any).eventDates.length).toBe(2)
  })
})
