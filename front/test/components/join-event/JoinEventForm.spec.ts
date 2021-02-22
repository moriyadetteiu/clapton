import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import JoinEventForm from '~/components/join-event/JoinEventForm.vue'
import VDialogStub from '~/test/stubs/VDialog.vue'
import EventFactory from '~/test/factory/EventFactory'
import TeamFactory from '~/test/factory/TeamFactory'
import EventDateFactory from '~/test/factory/EventDateFactory'
import JoinEventDateFactory from '~/test/factory/JoinEventDateFactory'

describe('join event from', () => {
  test('succeed case', async () => {
    const mutate = jest.fn((option) => {
      return new Promise<void>((resolve) => {
        resolve()
      })
    })
    const push = jest.fn()
    const success = jest.fn()

    const team = new TeamFactory().make()
    const event = new EventFactory().make()

    const eventDates = [...new Array(3)].map(() => {
      return new EventDateFactory().make({ event })
    })

    const joinEventDates = eventDates.map((eventDate) => {
      return new JoinEventDateFactory().make({
        eventDate: eventDate,
      })
    })

    let isOpen = true
    const wrapper = mount(JoinEventForm, {
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
      stubs: { 'nuxt-link': true, 'v-dialog': VDialogStub },
      propsData: {
        isOpen,
        eventDates,
        joinEventDates,
        teamId: team.id,
        eventId: event.id,
        joinEventId: '',
      },
    })
    await flushPromises()
    expect(wrapper.text()).toContain('参加情報編集')

    wrapper.find('form').trigger('submit')
    await flushPromises()

    expect(mutate).toBeCalled()
    expect(success).toBeCalled()
    expect(wrapper.emitted().saved).toBeTruthy()
  })
})
