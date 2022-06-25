<template>
  <v-container>
    <v-row dense>
      <v-col cols="12">
        {{ circleProduct.name }}
      </v-col>
    </v-row>

    <v-row dense>
      <v-col cols="12">
        <v-validate-text-field
          v-model="input.quantity"
          type="number"
          :validation="validation.getItem('quantity')"
        />
      </v-col>
    </v-row>

    <v-row dense>
      <v-col cols="12">
        <v-validate-select
          v-model="input.want_priority_id"
          :items="wantPriorities"
          item-text="name"
          item-value="id"
          :validation="validation.getItem('want_priority_id')"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Component, Prop } from 'nuxt-property-decorator'
import AbstractFormInput from '~/components/form/AbstractFormInput.vue'
import {
  CircleProduct,
  WantCircleProductInput,
  WantPrioritiesQuery,
  WantPriority,
} from '~/apollo/graphql'
import { CreateWantCircleProductInputValidation } from '~/validation/validations'

@Component({
  apollo: {
    wantPriorities: {
      query: WantPrioritiesQuery,
      variables() {
        const teamId = this.teamId
        return { teamId }
      },
    },
  },
})
export default class WantMeTooFormInput extends AbstractFormInput<
  WantCircleProductInput,
  CreateWantCircleProductInputValidation
> {
  @Prop({ type: String, required: true })
  private teamId!: string

  @Prop({ type: Object as PropType<CircleProduct>, required: true })
  private circleProduct!: string

  private wantPriorities: WantPriority[] = []
}
</script>
