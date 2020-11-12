<template>
  <team-form :team="team">
    <v-btn color="success" @click="submit">登録</v-btn>
  </team-form>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { TeamInput, CreateTeamMutation } from '~/apollo/graphql'
import TeamForm from '~/components/teams/TeamForm.vue'

@Component({
  components: {
    TeamForm,
  },
})
export default class CreateTeam extends Vue {
  team: TeamInput = {
    name: '',
  }

  private submit(): void {
    const res = this.$apollo.mutate({
      mutation: CreateTeamMutation,
      variables: {
        input: this.team,
      },
    })

    res
      .then(() => {
        // TODO: リダイレクト処理をかける
      })
      .catch(() => {
        // TODO: バリデーション失敗時にはエラーが出るようにする
      })
  }
}
</script>
