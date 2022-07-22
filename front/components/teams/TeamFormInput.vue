<template>
  <v-container>
    <v-row dense>
      <v-col cols="12">
        <v-text-field v-model="input.name" label="チーム名" outlined />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import AbstractFormInput from '~/components/form/AbstractFormInput.vue'
import { TeamInput } from '~/apollo/graphql'
import { CreateTeamInputValidation } from '~/validation/validations'

@Component
export default class TeamFormInput extends AbstractFormInput<
  TeamInput,
  CreateTeamInputValidation
> {}
</script>
