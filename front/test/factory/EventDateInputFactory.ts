import { EventDateInput } from '~/apollo/graphql'
import Factory from './Factory'
import moment from 'moment'

export default class EventDateInputFactory extends Factory<EventDateInput> {
  protected provide(): EventDateInput {
    return {
      name: this.faker.name.title(),
      date: moment(this.faker.date.between('2000-01-01', '2020-01-01')).format(
        'Y-m-d'
      ),
      is_production_day: this.faker.random.boolean(),
    }
  }
}
