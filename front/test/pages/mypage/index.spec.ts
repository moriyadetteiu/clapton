import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import MyPage from '~/pages/mypage/index.vue'
import UserFactory from '~/test/factory/UserFactory'
import TeamFactory from '~/test/factory/TeamFactory'

describe('my page index', () => {
  test('see contents', async () => {
    const user = new UserFactory().make()
    const team = new TeamFactory().make()
    user.affiliateTeams = [
      {
        id: '',
        team: team,
      },
    ]

    const wrapper = mount(MyPage, {
      stubs: ['nuxt-link'],
    })

    wrapper.setData({
      user,
    })
    await flushPromises()

    expect(wrapper.text()).toContain(user.name)
    expect(wrapper.text()).toContain(user.name_kana)

    expect(wrapper.text()).toContain(team.name)
  })
})
