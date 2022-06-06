import { JoinEvent } from '~/apollo/graphql'
import Factory from './Factory'
import UserFactory from './UserFactory'
import TeamFactory from './TeamFactory'
import EventFactory from './EventFactory'

const userFactory = new UserFactory()
const teamFactory = new TeamFactory()
const eventFactory = new EventFactory()

export default class JoinEventDateFactory extends Factory<JoinEvent> {
  protected provide(): JoinEvent {
    return {
      id: this.faker.datatype.uuid(),
      user: userFactory.make(),
      team: teamFactory.make(),
      event: eventFactory.make(),
      joinEventDates: [],
    }
  }
}
