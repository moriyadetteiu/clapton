import { WantPriority } from '~/apollo/graphql'
import Factory from './Factory'

export default class WantPriorityFactory extends Factory<WantPriority> {
  protected provide(): WantPriority {
    return {
      id: this.faker.random.uuid(),
      name: this.faker.name.title(),
    }
  }
}
