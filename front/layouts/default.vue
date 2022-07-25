<template>
  <v-app :dark="false">
    <v-app-bar fixed app>
      <wide-app-bar-content
        :underway-circle-list-items="underwayCircleListItems"
        :finished-circle-list-items="finishedCircleListItems"
        class="hidden-xs-only d-sm-flex"
        @logout="logout"
      />
      <narrow-app-bar-content
        class="d-flex d-sm-none"
        @open-navigation="openNavigation"
      />
    </v-app-bar>
    <narrow-app-bar-navigation
      v-model="isOpenNavigation"
      :underway-circle-list-items="underwayCircleListItems"
      :finished-circle-list-items="finishedCircleListItems"
      class="hidden-sm-and-up"
      @logout="logout"
    />

    <v-main>
      <v-container fluid>
        <confirm-dialog />
        <nuxt />
      </v-container>
    </v-main>
    <v-footer absolute app padless>
      <v-container>
        <v-row align="center">
          <app-logo-full />
          <v-spacer />
          <div>
            <v-switch
              v-model="isDark"
              label="ダークモード"
              dense
              flat
            ></v-switch>
          </div>
        </v-row>
      </v-container>
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
import { UnderwayEventItem } from '~/components/app-bar/AbstractAppBarContent.vue'
import WideAppBarContent from '~/components/app-bar/WideAppBarContent.vue'
import NarrowAppBarContent from '~/components/app-bar/NarrowAppBarContent.vue'
import NarrowAppBarNavigation from '~/components/app-bar/NarrowAppBarNavigation.vue'
import AppLogoFull from '~/components/logo/AppLogoFull.vue'

@Component({
  components: {
    ConfirmDialog,
    WideAppBarContent,
    NarrowAppBarContent,
    NarrowAppBarNavigation,
    AppLogoFull,
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
  private underwayCircleListItems: UnderwayEventItem[] = []
  private finishedCircleListItems: UnderwayEventItem[] = []
  private isOpenNavigation: boolean = false

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

  private openNavigation(): void {
    this.isOpenNavigation = true
  }
}
</script>
