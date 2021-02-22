import moment from 'moment'
import { JoinEventDate } from '~/apollo/graphql'
import Factory from './Factory'

export default class JoinEventDateFactory extends Factory<JoinEventDate> {
  protected provide(): JoinEventDate {
    return {
      id: this.faker.random.uuid(),
      is_join: this.faker.random.boolean(),
      number_of_tickets: this.faker.random.number(10),
    }
  }
}
