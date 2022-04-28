import { VuexModule, VuexMutation, Module } from 'nuxt-property-decorator'
import { User } from '~/apollo/graphql'

export interface UserState {
  loginUser: User | null
}

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
export default class UserModule extends VuexModule implements UserState {
  private _loginUser: User | null = null

  get loginUser(): User | null {
    return this._loginUser
  }

  get loginUserOrEmptyUser(): User {
    return this._loginUser || emptyUser
  }

  @VuexMutation
  public setLoginUser(user: User) {
    this._loginUser = user
  }

  @VuexMutation
  public logout() {
    this._loginUser = null
  }
}
