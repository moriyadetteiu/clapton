import { Event } from '~/apollo/graphql'
import Factory from './Factory'

export default class EventFactory extends Factory<Event> {
  protected provide(): Event {
    return {
      id: this.faker.datatype.uuid(),
      name: this.faker.name.title(),
    }
  }
}
