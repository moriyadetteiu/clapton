import { UserInput } from '~/apollo/graphql'
import Factory from './Factory'

export default class UserFactory extends Factory<UserInput> {
  protected provide(): UserInput {
    return {
      name: this.faker.name.title(),
      name_kana: this.faker.name.title(),
      handle_name: this.faker.name.title(),
      handle_name_kana: this.faker.name.title(),
      email: this.faker.internet.email(),
      password: this.faker.internet.password(),
    }
  }
}
