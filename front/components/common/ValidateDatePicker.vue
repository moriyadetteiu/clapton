<template>
  <validation-provider v-slot="{ errors }" :name="label" :rules="rules">
    <app-date-picker
      :date="date"
      :label="label"
      v-bind="$attrs"
      :error-messages="errors"
      @input="input"
    />
  </validation-provider>
</template>

<script lang="ts">
import { Vue, Prop, Model, Emit, Component } from 'nuxt-property-decorator'
import AppDatePicker from '~/components/common/AppDatePicker.vue'

@Component({
  components: { AppDatePicker },
  inheritAttrs: false,
})
export default class ValidateDatePicker extends Vue {
  @Model('input', { type: String, required: true })
  private date!: String

  @Prop({ type: String })
  private label?: String

  @Prop({ type: String, required: true })
  private rules!: String

  @Emit()
  input(inputValue: string) {
    return inputValue
  }
}
</script>
