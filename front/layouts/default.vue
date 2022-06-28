<template>
  <v-app :dark="false">
    <wide-app-bar
      :underway-circle-list-items="underwayCircleListItems"
      :finished-circle-list-items="finishedCircleListItems"
      @logout="logout"
    />
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
  UserAffiliationTeam,
  LogoutMutation,
  UnderwayEventsForJoinedTeamsQuery,
  FinishedEventsForJoinedTeamsQuery,
} from '~/apollo/graphql'
import { userStore } from '~/store'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'
import WideAppBar, {
  UnderwayEventItem,
} from '~/components/app-bar/WideAppBar.vue'

@Component({
  components: {
    ConfirmDialog,
    WideAppBar,
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
