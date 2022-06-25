<template>
  <v-app :dark="false">
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
    <v-main>
      <v-container fluid>
        <confirm-dialog />
        <nuxt />
      </v-container>
    </v-main>
    <v-footer absolute app padless>
      <span>clapton</span>
      <v-spacer />
      <div>
        <v-switch v-model="isDark" label="ダークモード" dense flat></v-switch>
      </div>
    </v-footer>
  </v-app>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import {
  User,
  Event,
  Team,
  UserAffiliationTeam,
  LogoutMutation,
  UnderwayEventsForJoinedTeamsQuery,
  FinishedEventsForJoinedTeamsQuery,
} from '~/apollo/graphql'
import { userStore } from '~/store'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'

type UnderwayEventItem = {
  team: Team
  event: Event
}

@Component({
  components: {
    ConfirmDialog,
  },
  apollo: {
    underwayCircleListItems: {
      query: UnderwayEventsForJoinedTeamsQuery,
      variables() {
        return { id: this.user.id }
      },
      skip() {
        return !this.user
      },
      update(data): UnderwayEventItem[] {
        return data.user.affiliateTeams.flatMap(
          (affiliationTeam: UserAffiliationTeam) => {
            const team = affiliationTeam.team!
            const events = team.underwayEvents as Array<Event>

            return events.map((event) => {
              return {
                event,
                team,
              }
            })
          }
        )
      },
    },
    finishedCircleListItems: {
      query: FinishedEventsForJoinedTeamsQuery,
      variables() {
        return { id: this.user.id }
      },
      skip() {
        return !this.user
      },
      update(data): UnderwayEventItem[] {
        return data.user.affiliateTeams.flatMap(
          (affiliationTeam: UserAffiliationTeam) => {
            const team = affiliationTeam.team!
            const events = team.finishedEvents as Array<Event>

            return events.map((event) => {
              return {
                event,
                team,
              }
            })
          }
        )
      },
    },
  },
})
export default class DefaultLayout extends Vue {
  underwayCircleListItems: UnderwayEventItem[] = []
  finishedCircleListItems: UnderwayEventItem[] = []

  private logout(): void {
    this.$apollo
      .mutate({
        mutation: LogoutMutation,
      })
      .then(() => {
        this.user = null
        this.$defaultApolloClient.resetStore()
        this.$toast.success('ログアウトしました。')
        this.$router.push('/login')
      })
  }

  // TODO: テストを実行できるように?.にしているのを、テスト側で対応するようにする
  get isDark(): boolean {
    return this?.$vuetify?.theme?.dark || false
  }

  set isDark(dark) {
    if (this?.$vuetify?.theme?.dark !== undefined) {
      this.$vuetify.theme.dark = dark
    }
  }

  private get headerMenuMaxHeight(): string {
    return 'calc(100vh - 100px)'
  }

  private get user(): User | null {
    return userStore.loginUser
  }

  private set user(user: User | null) {
    if (user) {
      userStore.setLoginUser(user)
    } else {
      userStore.logout()
    }
  }
}
</script>
