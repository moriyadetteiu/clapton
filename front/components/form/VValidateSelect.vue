<template>
  <validation-provider
    v-slot="{ errors }"
    :vid="vid"
    :name="validationLabel"
    :rules="validationRules"
  >
    <v-select
      :label="validationLabel"
      v-bind="$attrs"
      :error-messages="[...errors, ...safeBackendErrors]"
      v-on="listeners"
    />
  </validation-provider>
</template>

<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import AbstractVValidateInput from './AbstractVValidateInput.vue'

// @ts-ignore
@Component({
  inheritAttrs: false,
})
export default class VValidateSelect extends AbstractVValidateInput {
  protected readonly inputComponentName: string = 'v-select'
}
</script>
