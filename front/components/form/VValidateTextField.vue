<template>
  <validation-provider
    v-slot="{ errors }"
    :vid="vid"
    :name="validationLabel"
    :rules="validationRules"
  >
    <v-text-field
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

// TODO: templateをAbstractVValidateへ移譲
//       現状component isでやろうとすると、テストでこける
// @ts-ignore
@Component({
  inheritAttrs: false,
})
export default class VValidateTextField extends AbstractVValidateInput {
  protected readonly inputComponentName: string = 'v-text-field'
}
</script>
