import { User } from '~/apollo/graphql'
import Factory from './Factory'

export default class UserFactory extends Factory<User> {
  protected provide(): User {
    return {
      id: this.faker.random.uuid(),
      name: this.faker.name.title(),
      name_kana: this.faker.name.title(),
      handle_name: this.faker.name.title(),
      handle_name_kana: this.faker.name.title(),
      email: this.faker.internet.email(),
    }
  }
}
