<template>
  <v-app-bar fixed app>
    <v-container v-if="user !== null">
      <v-menu open-on-hover offset-y :max-height="headerMenuMaxHeight">
        <template #activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on"> リスト </v-btn>
        </template>
        <v-list dense>
          <v-list-item
            v-for="(item, idx) in underwayCircleListItems"
            :key="idx"
            nuxt
            :to="`/teams/${item.team.id}/events/${item.event.id}/circle-list`"
          >
            {{ item.team.name }}
            （ {{ item.event.name }} ）
          </v-list-item>
        </v-list>
      </v-menu>
      <v-menu open-on-hover offset-y :max-height="headerMenuMaxHeight">
        <template #activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on"> 過去リスト </v-btn>
        </template>
        <v-list dense>
          <v-list-item
            v-for="(item, idx) in finishedCircleListItems"
            :key="idx"
            nuxt
            :to="`/teams/${item.team.id}/events/${item.event.id}/circle-list`"
          >
            {{ item.team.name }}
            （ {{ item.event.name }} ）
          </v-list-item>
        </v-list>
      </v-menu>
      <v-btn text nuxt to="/favorites">お気に入り</v-btn>
    </v-container>
    <v-spacer />
    <template v-if="user !== null">
      <v-btn text nuxt to="/mypage">{{ user.name }}さん</v-btn>
      <v-btn text @click.prevent="logout">ログアウト</v-btn>
    </template>
    <template v-else>
      <v-btn text nuxt to="/login">ログイン</v-btn>
    </template>
  </v-app-bar>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop, Emit } from 'nuxt-property-decorator'
import { User, Event, Team } from '~/apollo/graphql'
import { userStore } from '~/store'

export type UnderwayEventItem = {
  team: Team
  event: Event
}

@Component({})
export default class DefaultLayout extends Vue {
  @Prop({ type: Array as PropType<UnderwayEventItem[]> })
  protected underwayCircleListItems!: UnderwayEventItem[]

  @Prop({ type: Array as PropType<UnderwayEventItem[]> })
  protected finishedCircleListItems!: UnderwayEventItem[]

  @Emit('logout')
  private logout(): void {}

  private get headerMenuMaxHeight(): string {
    return 'calc(100vh - 100px)'
  }

  private get user(): User | null {
    return userStore.loginUser
  }
}
</script>
