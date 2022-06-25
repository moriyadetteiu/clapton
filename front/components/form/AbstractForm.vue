<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import BaseValidation from '~/validation/validation'

// @ts-ignore
@Component({
  inheritAttrs: false,
})
export default abstract class AbstractForm<
  Validation extends BaseValidation | null
> extends Vue {
  protected abstract validation: Validation
}
</script>
