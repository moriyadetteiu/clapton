import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import JoinPage from '~/pages/teams/_team_id/join.vue'
import TeamFactory from '~/test/factory/TeamFactory'

describe('join to team page', () => {
  test('join', async () => {
    const mutate = jest.fn((option) => {
      return new Promise<void>((resolve) => {
        resolve()
      })
    })
    const push = jest.fn()
    const success = jest.fn()
    const team = new TeamFactory().make()

    const wrapper = mount(JoinPage, {
      mocks: {
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

    wrapper.setData({ team })
    await flushPromises()

    wrapper.find('.v-btn.register').trigger('click')
    await flushPromises()

    expect(mutate).toBeCalled()
    const teamId: string = mutate.mock.calls[0][0].variables.team_id
    expect(teamId).toBe(team.id)
    expect(push).toBeCalled()
    expect(success).toBeCalled()
  })
})
