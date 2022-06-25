<template>
  <v-container>
    <v-row dense>
      <v-col cols="12">
        <v-validate-text-field
          v-model="circleInput.name"
          :validation="validation.getItem('circle.name')"
        />
      </v-col>
    </v-row>

    <v-row dense>
      <v-col cols="12">
        <v-text-field
          v-model="circleInput.kana"
          placeholder="サークルの読み方"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Vue, PropSync, Component, Prop } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import { CircleInput, CirclePlacement } from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'

@Component({})
export default class CircleFormInput extends Vue {
  @Prop({
    type: Object as PropType<CreateCircleParticipatingInEventInputValidation>,
  })
  private validation!: CreateCircleParticipatingInEventInputValidation

  @PropSync('input', {
    type: Object as PropType<CirclePlacement>,
    required: true,
  })
  private circleInput!: CircleInput
}
</script>
