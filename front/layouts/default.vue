<template>
  <v-app :dark="false">
    <v-app-bar fixed app>
      <v-menu open-on-hover offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on"> リスト </v-btn>
        </template>
        <v-list>
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
      <v-menu open-on-hover offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on"> 過去リスト </v-btn>
        </template>
        <v-list>
          <v-list-item v-for="(listItem, idx) in oldListItems" :key="idx">
            {{ listItem.event_name }} ( {{ listItem.team_name }} )
          </v-list-item>
        </v-list>
      </v-menu>
      <v-spacer />
      <template v-if="user !== null">
        <v-btn text nuxt to="/mypage">{{ user.name }}さん</v-btn>
        <v-btn text @click.prevent="logout">ログアウト</v-btn>
      </template>
    </v-app-bar>
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <v-footer absolute app padless>
      <span>clapton</span>
      <v-spacer />
      <div>
        <v-switch
          v-model="$vuetify.theme.dark"
          label="ダークモード"
          dense
          flat
        ></v-switch>
      </div>
    </v-footer>
  </v-app>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import {
  User,
  MeQuery,
  Event,
  Team,
  UserAffiliationTeam,
  LogoutMutation,
  UnderwayEventsForJoinedTeamsQuery,
} from '~/apollo/graphql'

type UnderwayCircleListItem = {
  team: Team
  event: Event
}

@Component({
  apollo: {
    user: {
      query: MeQuery,
      skip() {
        return !this.$apolloHelpers.getToken()
      },
      update(data): User {
        return data.me
      },
    },
    underwayCircleListItems: {
      query: UnderwayEventsForJoinedTeamsQuery,
      variables() {
        return { id: this.user.id }
      },
      skip() {
        return !this.user
      },
      update(data): UnderwayCircleListItem[] {
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
  },
})
export default class DefaultLayout extends Vue {
  underwayCircleListItems: UnderwayCircleListItem[] = []

  oldListItems: {
    event_id: string // eslint-disable-line camelcase
    event_name: string // eslint-disable-line camelcase
    team_name: string // eslint-disable-line camelcase
  }[] = [{ event_id: 'aaa', event_name: 'event', team_name: 'team' }]

  user: User | null = null

  private logout(): void {
    this.$apollo
      .mutate({
        mutation: LogoutMutation,
      })
      .then(async () => {
        this.user = null
        await this.$apolloHelpers.onLogout()
        this.$toast.success('ログアウトしました。')
        this.$router.push('/login')
      })
  }
}
</script>
