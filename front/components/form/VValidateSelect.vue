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
import AbstractVValidateInput from './AbstractVValidateInput.vue'

export default class VValidateSelect extends AbstractVValidateInput {
  protected readonly inputComponentName: string = 'v-select'
}
</script>
