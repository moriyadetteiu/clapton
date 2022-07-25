import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import DefaultLayout from '~/layouts/default.vue'
import UserFactory from '~/test/factory/UserFactory'
import EventFactory from '~/test/factory/EventFactory'
import TeamFactory from '~/test/factory/TeamFactory'
import { resetStore, store } from '~/test/utils/vuex-store'

const makeWrapper = () => {
  return mount(DefaultLayout, {
    store,
    stubs: [
      'nuxt-link',
      'v-app',
      'v-app-bar',
      'v-app-bar-nav-icon',
      'narrow-app-bar-navigation',
      'v-main',
      'v-menu',
      'v-footer',
      'v-switch',
      'nuxt',
    ],
  })
}

describe('default layout', () => {
  beforeEach(() => {
    resetStore()
  })

  test('display during logout', async () => {
    const wrapper = makeWrapper()

    await flushPromises()
    expect(wrapper.text()).toContain('ログイン')
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

    const finishedCircleListItems = underwayCircleListItems

    const wrapper = makeWrapper()
    wrapper.setData({
      user,
      underwayCircleListItems,
      finishedCircleListItems,
    })

    await flushPromises()
    expect(wrapper.text()).toContain('ログアウト')
    expect(wrapper.text()).toContain(event.name)
    expect(wrapper.text()).toContain(team.name)
  })
})
