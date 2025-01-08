import moment from 'moment'
import { EventDate } from '~/apollo/graphql'
import Factory from './Factory'

export default class EventDateFactory extends Factory<EventDate> {
  protected provide(): EventDate {
    const name = this.faker.name.title()
    const date = moment(this.faker.date.between('2000-01-01', '2020-01-01')).format(
      'Y-m-d'
    )

    return {
      id: this.faker.datatype.uuid(),
      name,
      date,
      full_format_date: `${name}（${date}）`,
      is_production_day: this.faker.datatype.boolean(),
    }
  }
}
