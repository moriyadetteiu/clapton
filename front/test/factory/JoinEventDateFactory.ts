import moment from 'moment'
import { JoinEventDate } from '~/apollo/graphql'
import Factory from './Factory'
import JoinEventFactory from './JoinEventFactory'
import EventDateFactory from './EventDateFactory'

const joinEventFactory = new JoinEventFactory()
const eventDateFactory = new EventDateFactory()

export default class JoinEventDateFactory extends Factory<JoinEventDate> {
  protected provide(): JoinEventDate {
    const eventDate = eventDateFactory.make()

    return {
      id: this.faker.datatype.uuid(),
      is_join: this.faker.datatype.boolean(),
      number_of_tickets: this.faker.datatype.number(10),
      joinEvent: joinEventFactory.make(),
      eventDate: eventDate,
      event_date_id: eventDate.id,
    }
  }
}
