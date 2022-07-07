<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { ValidationObserver } from 'vee-validate'
import { isApolloError, ApolloError } from 'apollo-client/errors/ApolloError'
import { GraphQLError } from 'graphql'
import BaseValidation from '~/validation/validation'

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
  protected afterMutate(data: any) {
    return data
  }

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
      error.graphQLErrors.forEach((graphQLError: GraphQLError) => {
        this.handleGraphQLError(graphQLError, error)
      })
    }
  }

  protected handleGraphQLError(
    graphQLError: GraphQLError,
    apolloError: ApolloError
  ) {
    if (graphQLError.extensions.category === 'validation') {
      this.handleValidationError(apolloError)
    }
  }

  protected handleValidationError(error: ApolloError) {
    this.$toasted.global.validationError()
    if (this.validation) {
      this.validation.setBackendErrorsFromAppolo(error)
    }
  }
}
</script>
