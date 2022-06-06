import { Team } from '~/apollo/graphql'
import Factory from './Factory'

export default class TeamFactory extends Factory<Team> {
  protected provide(): Team {
    return {
      id: this.faker.datatype.uuid(),
      code: this.faker.random.words(10),
      name: this.faker.name.title(),
      userAffiliationTeams: [],
    }
  }
}
