<template>
  <v-tooltip top>
    <template #activator="{ on, attrs }">
      <v-btn
        icon
        color="favorite"
        v-bind="{ ...attrs, ...$attrs }"
        v-on="on"
        @click.stop="toggleFavorite"
      >
        <v-icon> {{ mdiIconName }} </v-icon>
      </v-btn>
    </template>
    <span>{{ tooltipMessage }}</span>
  </v-tooltip>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import {
  Favorite,
  CreateFavoriteMutation,
  DeleteFavoriteMutation,
} from '~/apollo/graphql'
import { userStore, favoriteStore } from '~/store'

/**
 * note: このコンポーネントはfavoriteStore（vuex）のmyFavoritesが設定されている前提で使用する必要がある
 */
@Component({ inheritAttrs: false })
export default class FavoriteButton extends Vue {
  @Prop({
    type: String,
    required: true,
  })
  private circleId!: string

  private get favorite(): Favorite | null {
    return favoriteStore.findMyFavorite(this.circleId)
  }

  private get userId(): string {
    return userStore.loginUserOrEmptyUser.id
  }

  private get isFavorite(): boolean {
    return Boolean(this.favorite)
  }

  private get mdiIconName(): string {
    return this.isFavorite ? 'mdi-star' : 'mdi-star-outline'
  }

  private get tooltipMessage(): string {
    return this.isFavorite ? 'お気に入りを解除' : 'お気に入りに追加'
  }

  private async toggleFavorite() {
    try {
      await (this.isFavorite ? this.deleteFavorite() : this.createFavorite())
      await favoriteStore.fetchMyFavorites()
    } catch (e) {
      this.$toast.error('更新に失敗しました')
    }
  }

  private createFavorite(): Promise<any> {
    const input = {
      user_id: this.userId,
      circle_id: this.circleId,
    }

    return this.$apollo
      .mutate({
        mutation: CreateFavoriteMutation,
        variables: {
          input,
        },
      })
      .then(() => {
        this.$toast.success('お気に入りに追加しました')
      })
  }

  private deleteFavorite(): Promise<any> {
    const variables = {
      id: this.favorite!.id,
    }

    return this.$apollo
      .mutate({
        mutation: DeleteFavoriteMutation,
        variables,
      })
      .then(() => {
        this.$toast.success('お気に入りの登録を解除しました')
      })
  }
}
</script>
