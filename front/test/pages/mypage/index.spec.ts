import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import MyPage from '~/pages/mypage/index.vue'
import UserFactory from '~/test/factory/UserFactory'
import TeamFactory from '~/test/factory/TeamFactory'
import { store, resetStore, userStore } from '~/test/utils/vuex-store'

describe('my page index', () => {
  beforeEach(() => {
    resetStore()
  })

  test('see contents', async () => {
    const user = new UserFactory().make()
    const team = new TeamFactory().make()
    user.affiliateTeams = [
      {
        id: '',
        team: team,
        user,
      },
    ]

    userStore.setLoginUser(user)
    const wrapper = mount(MyPage, {
      store,
      stubs: ['nuxt-link'],
    })
    await flushPromises()

    expect(wrapper.text()).toContain(user.name)
    expect(wrapper.text()).toContain(user.name_kana)

    expect(wrapper.text()).toContain(team.name)
  })
})
