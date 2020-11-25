<template>
  <validation-provider
    :vid="vid"
    :name="label"
    :rules="rules"
    v-slot="{ errors }"
  >
    <v-text-field
      :label="label"
      v-bind="$attrs"
      v-on="listeners"
      :error-messages="errors"
    />
  </validation-provider>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'

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
