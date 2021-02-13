import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import TeamPage from '~/pages/teams/_team_id/index.vue'
import TeamFactory from '~/test/factory/TeamFactory'

describe('team page index', () => {
  test('see team name', async () => {
    const team = new TeamFactory().make()

    const wrapper = mount(TeamPage, {
      mocks: {
        $config: {
          BASE_URL: 'http://localhost:3000',
        },
      },
      stubs: ['nuxt-link'],
    })

    wrapper.setData({
      team,
      $config: {
        BASE_URL: 'http://localhost:3000',
      },
    })
    await flushPromises()

    expect(wrapper.text()).toContain(team.name)
  })
})
