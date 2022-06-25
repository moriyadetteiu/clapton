<script lang="ts">
import { Vue, Prop, Component, Watch } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import BaseValidation from '~/validation/validation'
import { ValidationObserver } from 'vee-validate'
import { isApolloError } from 'apollo-client/errors/ApolloError'
import Validation from '~/validation/validation'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'

// @ts-ignore
@Component({
  inheritAttrs: false,
})
export default abstract class AbstractForm<
  Validation extends BaseValidation | null
> extends Vue {
  protected abstract validation: Validation | null

  // return $apollo.mutate() のようなミューテーションの実態を定義するメソッド
  protected abstract mutate(): Promise<any>

  // note: 必要に応じてオーバーライドして使う
  protected afterMutate(data: any) {}

  protected migrateModelToInput(input: any, model: any): any {
    const workInput: any = {}
    Object.keys(input).forEach((key) => {
      workInput[key] = model[key]
    })
    return workInput
  }

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  protected async submit(): Promise<void> {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      try {
        const data = await this.mutate()

        this.afterMutate(data)

        this.$toast.success('保存しました')
      } catch (error: any) {
        this.handleError(error)
      }
    }
  }

  protected handleError(error: any): void {
    if (isApolloError(error)) {
      this.$toasted.global.validationError()
      if (this.validation) {
        this.validation.setBackendErrorsFromAppolo(error)
      }
    }
  }
}
</script>
