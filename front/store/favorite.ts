import {
  VuexModule,
  VuexMutation,
  Module,
  VuexAction,
} from 'nuxt-property-decorator'
import { Favorite, MyFavoritesQuery } from '~/apollo/graphql'

@Module({
  name: 'favorite',
  stateFactory: true,
  namespaced: true,
})
export default class FavoriteModule extends VuexModule {
  private _myFavorites: Favorite[] = []

  public get myFavorites(): Favorite[] {
    return this._myFavorites
  }

  public get findMyFavorite(): (circleId: string) => Favorite | null {
    return (circleId: string) =>
      this._myFavorites.find((favorite) => favorite.circle_id === circleId) ??
      null
  }

  @VuexMutation
  public setMyFavorites(myFavorites: Favorite[]): void {
    this._myFavorites = myFavorites
  }

  @VuexAction
  public async fetchMyFavorites() {
    const myFavorites = await this.store.$defaultApolloClient
      .query({
        query: MyFavoritesQuery,
        fetchPolicy: 'network-only',
      })
      .then<Favorite[]>((result) => result.data.myFavorites)

    this.setMyFavorites(myFavorites)
  }
}
