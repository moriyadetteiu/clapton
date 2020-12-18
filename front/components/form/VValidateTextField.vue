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
      :error-messages="errors"
      v-on="listeners"
    />
  </validation-provider>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import { ValidationItem } from '~/validation/validation'

@Component({
  inheritAttrs: false,
})
export default class VValidateTextField extends Vue {
  @Prop({ type: String })
  private label?: String

  @Prop({ type: String })
  private rules?: String

  @Prop({ type: String })
  private vid?: String

  @Prop({ type: Object as PropType<ValidationItem> } as PropOptions<
    ValidationItem
  >)
  private validation?: ValidationItem

  get validationRules(): String {
    return this.rules ?? this.validation?.rules ?? ''
  }

  get validationLabel(): String {
    return this.label ?? this.validation?.attribute ?? ''
  }

  get listeners() {
    return {
      ...this.$listeners,
      input: (event: any) => this.$emit('input', event),
    }
  }
}
</script>
