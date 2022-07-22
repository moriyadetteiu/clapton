<template>
  <validation-observer
    ref="validationObserver"
    tag="form"
    @submit.prevent="submit"
  >
    <team-form-input v-model="team" />
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <submit-btn>登録</submit-btn>
          <v-btn @click="$emit('canceled')">キャンセル</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import 'vue-apollo'
import { Component } from 'nuxt-property-decorator'
import { TeamInput, CreateTeamMutation } from '~/apollo/graphql'
import AbstractForm from '~/components/form/AbstractForm.vue'
import TeamFormInput from '~/components/teams/TeamFormInput.vue'
import { CreateTeamInputValidation } from '~/validation/validations'

@Component({
  head() {
    return {
      title: 'チーム追加',
    }
  },
  components: {
    TeamFormInput,
  },
})
export default class CreateTeam extends AbstractForm<CreateTeamInputValidation> {
  protected validation: CreateTeamInputValidation =
    new CreateTeamInputValidation()

  team: TeamInput = {
    name: '',
  }

  protected mutate(): Promise<any> {
    return this.$apollo.mutate({
      mutation: CreateTeamMutation,
      variables: {
        input: this.team,
      },
    })
  }

  protected afterMutate(data: any): void {
    this.$router.push(`/teams/${data.data.createTeam.id}`)
  }
}
</script>
