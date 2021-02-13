import { CirclePlacementClassification } from '~/apollo/graphql'
import Factory from './Factory'

export default class CirclePlacementClassificationFactory extends Factory<
  CirclePlacementClassification
> {
  protected provide(): CirclePlacementClassification {
    return {
      id: this.faker.random.uuid(),
      name: this.faker.name.title(),
      cost: this.faker.random.number(10),
    }
  }
}
