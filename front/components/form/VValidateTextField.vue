<template>
  <validation-provider
    v-slot="{ errors }"
    :vid="vid"
    :name="label"
    :rules="rules"
  >
    <v-text-field
      :label="label"
      v-bind="$attrs"
      :error-messages="errors"
      v-on="listeners"
    />
  </validation-provider>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class VValidateTextField extends Vue {
  @Prop({ type: String })
  private label?: String

  @Prop({ type: String, required: true })
  private rules!: String

  @Prop({ type: String })
  private vid?: String

  get listeners() {
    return {
      ...this.$listeners,
      input: (event: any) => this.$emit('input', event),
    }
  }
}
</script>
