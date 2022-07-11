<template>
  <v-container>
    <v-row dense>
      <v-col cols="12">
        <v-validate-text-field
          v-model="input.name"
          :disabled="disabled"
          :validation="validation.getItem('circle.name')"
        />
      </v-col>
    </v-row>

    <v-row dense>
      <v-col cols="12">
        <v-text-field
          v-model="input.kana"
          :disabled="disabled"
          placeholder="サークルの読み方"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Prop } from 'nuxt-property-decorator'
import AbstractFormInput from '~/components/form/AbstractFormInput.vue'
import { CircleInput } from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'

@Component({})
export default class CircleFormInput extends AbstractFormInput<
  CircleInput,
  CreateCircleParticipatingInEventInputValidation
> {
  @Prop({ type: Boolean, default: false })
  private disabled!: boolean
}
</script>
