import { EventInput } from '~/apollo/graphql'
import Factory from './Factory'

export default class EventInputFactory extends Factory<EventInput> {
  protected provide(): EventInput {
    return {
      name: this.faker.name.title(),
      team_id: this.faker.random.uuid(),
      event_dates: [],
    }
  }
}
