<template>
  <validation-provider
    v-slot="{ errors }"
    :vid="vid"
    :name="validationLabel"
    :rules="validationRules"
  >
    <component
      :is="inputComponentName"
      :label="validationLabel"
      v-bind="$attrs"
      :error-messages="[...errors, ...safeBackendErrors]"
      v-on="listeners"
    />
  </validation-provider>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import { ValidationItem } from '~/validation/validation'

// @ts-ignore
@Component({
  inheritAttrs: false,
})
export default abstract class AbstractVValidateInput extends Vue {
  protected abstract readonly inputComponentName: string

  @Prop({ type: String })
  private label?: String

  @Prop({ type: String })
  private rules?: String

  @Prop({ type: String })
  private vid?: String

  @Prop({
    type: Object as PropType<ValidationItem>,
  } as PropOptions<ValidationItem>)
  private validation?: ValidationItem

  @Prop({ type: Array as PropType<String[]> })
  private backendErrors?: String[]

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

  get safeBackendErrors() {
    return (this.backendErrors ?? []).concat(
      this.validation?.backendErrors ?? []
    )
  }
}
</script>
