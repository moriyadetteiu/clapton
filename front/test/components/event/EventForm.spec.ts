import { mount } from '@vue/test-utils'
import flushPromises from 'flush-promises'
import EventForm from '~/components/events/EventForm.vue'
import EventInputFactory from '~/test/factory/EventInputFactory'
import EventDateInputFactory from '~/test/factory/EventDateInputFactory'

describe('event from', () => {
  test('join', async () => {
    const mutate = jest.fn((option) => {
      return new Promise<void>((resolve) => {
        resolve()
      })
    })
    const push = jest.fn()
    const success = jest.fn()

    const eventInput = new EventInputFactory().make()
    const eventDateInput = new EventDateInputFactory().make()

    const wrapper = mount(EventForm, {
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
      propsData: {
        event: eventInput,
        eventDates: [eventDateInput],
      },
    })
    await flushPromises()
    expect(wrapper.text()).toContain('イベント当日')

    wrapper.find('.v-btn.success').trigger('click')
    await flushPromises()

    expect(wrapper.emitted().submit).toBeTruthy()
  })
})
