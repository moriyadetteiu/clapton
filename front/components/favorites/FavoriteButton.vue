<template>
  <v-btn icon v-bind="$attrs" @click="toggleFavorite">
    <v-icon> {{ mdiIconName }} </v-icon>
  </v-btn>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import {
  Favorite,
  CreateFavoriteMutation,
  DeleteFavoriteMutation,
} from '~/apollo/graphql'

@Component({ inheritAttrs: false })
export default class FavoriteButton extends Vue {
  // note: propでの受け渡しをしているが、お気に入りはログイン中のユーザのものしか取り扱わないデータのため
  //       vuexへの移行を検討している
  @Prop({
    type: Object as PropType<Favorite>,
  })
  private favorite!: Favorite | null

  @Prop({
    type: String,
    required: true,
  })
  private userId!: string

  @Prop({
    type: String,
    required: true,
  })
  private circleId!: string

  private get isFavorite(): boolean {
    return Boolean(this.favorite)
  }

  private get mdiIconName(): string {
    return this.isFavorite ? 'mdi-star' : 'mdi-star-outline'
  }

  private toggleFavorite() {
    const result = this.isFavorite
      ? this.deleteFavorite()
      : this.createFavorite()

    result
      .then(() => {
        this.$emit('update-favorite')
      })
      .catch(() => {
        this.$toast.error('更新に失敗しました')
      })
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
