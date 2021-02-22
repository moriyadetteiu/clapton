import moment from 'moment'
import { EventDate } from '~/apollo/graphql'
import Factory from './Factory'

export default class EventDateFactory extends Factory<EventDate> {
  protected provide(): EventDate {
    return {
      id: this.faker.random.uuid(),
      name: this.faker.name.title(),
      date: moment(this.faker.date.between('2000-01-01', '2020-01-01')).format(
        'Y-m-d'
      ),
      is_production_day: this.faker.random.boolean(),
    }
  }
}
