<template>
  <v-container>
    <v-card>
      <v-card-title>チーム情報</v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="4" class="font-weight-bold">チーム名</v-col>
            <v-col cols="8">{{ team.name }}</v-col>
          </v-row>
          <v-row>
            <v-col cols="4" class="font-weight-bold">招待URL</v-col>
            <v-col cols="8">
              {{ $config.BASE_URL }}/teams/{{ team.id }}/join
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
    <v-card class="mt-3">
      <v-card-title>
        イベント
        <v-spacer />
        <register-btn nuxt :to="`/teams/${team.id}/events/create`" />
      </v-card-title>
    </v-card>
    <v-card class="mt-3">
      <v-card-title>マスタ管理</v-card-title>
      <v-card-actions>
        <v-btn nuxt :to="`/teams/${team.id}/circle-placement-classifications`">
          配置分類
        </v-btn>
        <v-btn nuxt :to="`/teams/${team.id}/circle-product-classifications`">
          頒布物分類
        </v-btn>
        <v-btn nuxt :to="`/teams/${team.id}/want-priorities`"> 優先度 </v-btn>
      </v-card-actions>
    </v-card>
    <v-card class="mt-3">
      <v-card-title>所属情報</v-card-title>
      <v-card-actions>
        <v-btn nuxt :to="`/teams/${team.id}/affiliation-users`">
          所属者一覧
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-container>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { Team, TeamQuery } from '~/apollo/graphql'

@Component({
  head() {
    return {
      title: (this as TeamPage).team.name,
    }
  },
  apollo: {
    team: {
      query: TeamQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { id: teamId }
      },
    },
  },
})
export default class TeamPage extends Vue {
  private team: Team = {
    id: '',
    name: '',
    code: '',
    userAffiliationTeams: [],
  }
}
</script>
