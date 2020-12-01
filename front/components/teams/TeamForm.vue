<template>
  <v-form>
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-text-field v-model="syncedTeam.name" label="チーム名" outlined />
        </v-col>
        <v-col cols="12">
          <slot />
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script lang="ts">
import { Vue, PropSync, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import { TeamInput } from '~/apollo/graphql'

@Component
export default class TeamForm extends Vue {
  @PropSync('team', { type: Object as PropType<TeamInput> } as PropOptions<
    TeamInput
  >)
  syncedTeam!: TeamInput
}
</script>
