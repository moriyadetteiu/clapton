import { CircleProductClassification } from '~/apollo/graphql'
import Factory from './Factory'

export default class CircleProductClassificationFactory extends Factory<
  CircleProductClassification
> {
  protected provide(): CircleProductClassification {
    return {
      id: this.faker.random.uuid(),
      name: this.faker.name.title(),
    }
  }
}
