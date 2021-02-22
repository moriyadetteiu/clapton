import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import DefaultLayout from '~/layouts/default.vue'
import UserFactory from '~/test/factory/UserFactory'
import EventFactory from '~/test/factory/EventFactory'
import TeamFactory from '~/test/factory/TeamFactory'

const makeWrapper = () => {
  return mount(DefaultLayout, {
    stubs: [
      'nuxt-link',
      'v-app',
      'v-app-bar',
      'v-main',
      'v-menu',
      'v-footer',
      'v-switch',
      'nuxt',
    ],
  })
}

describe('default layout', () => {
  test('display during logout', async () => {
    const wrapper = makeWrapper()

    await flushPromises()
    expect(wrapper.text()).toContain('clapton')
    expect(wrapper.text()).not.toContain('ログアウト')
  })

  test('display during login', async () => {
    const user = new UserFactory().make()
    const event = new EventFactory().make()
    const team = new TeamFactory().make()

    const underwayCircleListItems = [
      {
        team,
        event,
      },
    ]

    const wrapper = makeWrapper()
    wrapper.setData({
      user,
      underwayCircleListItems,
    })

    await flushPromises()
    expect(wrapper.text()).toContain('clapton')
    expect(wrapper.text()).toContain('ログアウト')
    expect(wrapper.text()).toContain(user.name)
    expect(wrapper.text()).toContain(event.name)
    expect(wrapper.text()).toContain(team.name)
  })
})
