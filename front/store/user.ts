import { VuexModule, VuexMutation, Module } from 'nuxt-property-decorator'
import { User } from '~/apollo/graphql'

export interface UserState {
  loginUser: User | null
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

  @VuexMutation
  public setLoginUser(user: User) {
    this._loginUser = user
  }

  @VuexMutation
  public logout() {
    this._loginUser = null
  }
}
