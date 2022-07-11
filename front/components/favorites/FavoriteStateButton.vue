<template>
  <v-menu v-model="isOpenMenu" offset-y>
    <template #activator="menuSlot">
      <v-tooltip top>
        <template #activator="{ on, attrs }">
          <v-btn
            icon
            :color="mdiIconColor"
            v-bind="{ ...attrs, ...menuSlot.attrs, ...$attrs }"
            v-on="{ ...on }"
            @click.stop="openMenu"
          >
            <v-icon> {{ mdiIconName }} </v-icon>
          </v-btn>
        </template>
        <span>{{ tooltipMessage }}</span>
      </v-tooltip>
    </template>
    <v-list>
      <v-list-item
        v-if="isNotParticipation"
        link
        @click="cancelNotParticipateCircleInEvent"
      >
        <v-list-item-title>
          <v-icon color="warning">mdi-help-circle</v-icon>
          不参加を取り消す
        </v-list-item-title>
      </v-list-item>
      <v-list-item
        v-else-if="isUnchecked"
        link
        @click="notParticipateCircleInEvent"
      >
        <v-list-item-title>
          <v-icon color="secondary">mdi-close-circle</v-icon>
          不参加にする
        </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import {
  FavoriteWithState,
  CancelNotParticipateCircleInEventMutation,
  NotParticipateCircleInEventMutation,
} from '~/apollo/graphql'
import InvalidArgumentException from '~/exceptions/InvalidArgumentException'

type FavoriteStateIcon = {
  name: string
  icon: string
  color: string
}

const icons: FavoriteStateIcon[] = [
  {
    name: '未確認',
    icon: 'mdi-help-circle',
    color: 'warning',
  },
  {
    name: '参加',
    icon: 'mdi-store-check',
    color: 'warning',
  },
  {
    name: '不参加',
    icon: 'mdi-close-circle',
    color: 'secondary',
  },
  {
    name: 'チェック済',
    icon: 'mdi-check-circle',
    color: 'primary',
  },
]

@Component({ inheritAttrs: false })
export default class FavoriteStateButton extends Vue {
  @Prop({
    type: Object as PropType<FavoriteWithState>,
    required: true,
  })
  private favoriteWithState!: FavoriteWithState

  @Prop({
    type: String,
    required: true,
  })
  private eventId!: string

  private isOpenMenu: boolean = false

  private get tooltipMessage(): string {
    return this.favoriteWithState.state
  }

  private get icon(): FavoriteStateIcon {
    const icon = icons.find(
      (searchIcon) => searchIcon.name === this.favoriteWithState.state
    )
    if (!icon) {
      throw new InvalidArgumentException(
        `${this.favoriteWithState.state}に対応するアイコンが設定されていません`
      )
    }
    return icon
  }

  private get mdiIconName(): string {
    return this.icon.icon
  }

  private get mdiIconColor(): string {
    return this.icon.color
  }

  private get isNotParticipation(): boolean {
    return this.favoriteWithState.state === '不参加'
  }

  private get isUnchecked(): boolean {
    return this.favoriteWithState.state === '未確認'
  }

  private openMenu(): void {
    if (!this.isNotParticipation && !this.isUnchecked) {
      return
    }
    this.isOpenMenu = true
  }

  private async notParticipateCircleInEvent(): Promise<any> {
    const variables = {
      circleId: this.favoriteWithState.favorite.circle!.id,
      eventId: this.eventId,
    }

    try {
      await this.$apollo.mutate({
        mutation: NotParticipateCircleInEventMutation,
        variables,
        refetchQueries: ['MyFavoritesInEventsQuery'],
      })

      this.$toast.success('不参加にしました')
    } catch (e) {
      this.$toast.error('エラーが発生しました')
    }
  }

  private async cancelNotParticipateCircleInEvent(): Promise<any> {
    const variables = {
      circleId: this.favoriteWithState.favorite.circle!.id,
      eventId: this.eventId,
    }

    try {
      await this.$apollo.mutate({
        mutation: CancelNotParticipateCircleInEventMutation,
        variables,
        refetchQueries: ['MyFavoritesInEventsQuery'],
      })

      this.$toast.success('不参加を取り消しました')
    } catch (e) {
      this.$toast.error('エラーが発生しました')
    }
  }
}
</script>
