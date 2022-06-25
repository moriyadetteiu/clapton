<template>
  <v-container>
    <favorite-table :favorites="favorites" />
  </v-container>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { Favorite } from '~/apollo/graphql'
import FavoriteTable from '~/components/favorites/FavoriteTable.vue'
import { favoriteStore } from '~/store'

@Component({
  head() {
    return {
      title: 'お気に入り',
    }
  },
  components: {
    FavoriteTable,
  },
})
export default class FavoritesPage extends Vue {
  private get favorites(): Favorite[] {
    return favoriteStore.myFavorites
  }

  public created() {
    favoriteStore.fetchMyFavorites()
  }
}
</script>
