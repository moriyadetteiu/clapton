<template>
  <v-card>
    <v-card-title>チームへの参加</v-card-title>
    <v-card-text>チーム名：{{ team.name }}</v-card-text>
    <v-card-actions>
      <v-btn color="register" @click="join">参加</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { Team, TeamQuery, JoinTeamMutation } from '~/apollo/graphql'

@Component({
  head() {
    return {
      title: `${(this as JoinPage).team.name}へ参加`,
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
export default class JoinPage extends Vue {
  private team: Team = {
    id: '',
    name: '',
    code: '',
    userAffiliationTeams: [],
  }

  private join(): void {
    const teamId = this.team.id

    this.$apollo
      .mutate({
        mutation: JoinTeamMutation,
        variables: {
          team_id: teamId,
        },
      })
      .then(() => {
        this.$toast.success('参加しました')
        this.$router.push(`/teams/${teamId}`)
      })
      .catch(() => {
        this.$toast.error('参加に失敗しました')
      })
  }
}
</script>
