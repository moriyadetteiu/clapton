<template>
  <v-app :dark="false">
    <v-app-bar fixed app>
      <v-menu open-on-hover offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on"> リスト </v-btn>
        </template>
        <v-list>
          <v-list-item v-for="(listItem, idx) in listItems" :key="idx">
            {{ listItem.team_name }}
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
        <v-btn text>ログアウト</v-btn>
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
import { User, MeQuery } from '~/apollo/graphql'

@Component({})
export default class DefaultLayout extends Vue {
  // eslint-disable-next-line camelcase
  listItems: { event_id: string; team_name: string }[] = [
    {
      event_id: 'aaa',
      team_name: 'test',
    },
    {
      event_id: 'bbb',
      team_name: 'test2',
    },
    {
      event_id: 'ccc',
      team_name: 'test3',
    },
  ]

  oldListItems: {
    event_id: string // eslint-disable-line camelcase
    event_name: string // eslint-disable-line camelcase
    team_name: string // eslint-disable-line camelcase
  }[] = [{ event_id: 'aaa', event_name: 'event', team_name: 'team' }]

  user: User | null = null

  async created() {
    if (this.$apolloHelpers.getToken()) {
      const me = await this.$apollo.query<{ me: User }>({
        query: MeQuery,
      })
      this.user = me.data.me
    }
  }

  mounted() {}
}
</script>
