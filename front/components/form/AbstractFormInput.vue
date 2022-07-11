<script lang="ts">
import { Vue, Model, Component, Prop } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import BaseValidation from '~/validation/validation'

@Component({})
export default class AbstractFormInput<
  Input,
  Validation extends BaseValidation | null
> extends Vue {
  @Prop({
    type: Object as PropType<Validation>,
  })
  protected validation!: Validation

  @Model('update:value', {
    type: Object as PropType<Input>,
    required: true,
  })
  protected value!: Input

  protected get input() {
    return this.value
  }

  protected set input(value) {
    this.$emit('update:value', value)
  }
}
</script>
