import { VuexModule, VuexMutation, Module } from 'nuxt-property-decorator'
import { User } from '~/apollo/graphql'

const emptyUser: User = {
  id: '',
  name: '',
  name_kana: '',
  handle_name: '',
  handle_name_kana: '',
  affiliateTeams: [],
}

@Module({
  name: 'user',
  stateFactory: true,
  namespaced: true,
})
export default class UserModule extends VuexModule {
  private _loginUser: User | null = null

  public get loginUser(): User | null {
    return this._loginUser
  }

  public get loginUserOrEmptyUser(): User {
    return this._loginUser || emptyUser
  }

  @VuexMutation
  public setLoginUser(user: User): void {
    this._loginUser = user
  }

  @VuexMutation
  public logout(): void {
    this._loginUser = null
  }
}
