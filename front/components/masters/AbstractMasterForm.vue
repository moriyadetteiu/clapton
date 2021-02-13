<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>{{ title }}</v-card-title>
      <v-card-text>
        <validation-observer
          ref="validationObserver"
          tag="v-form"
          @submit.prevent="submit"
        >
          <v-container>
            <v-row v-for="(inputField, idx) in inputFields" :key="idx">
              <v-col cols="12">
                <v-validate-text-field
                  v-model="input[inputField.name]"
                  outlined
                  :validation="validation.getItem(inputField.name)"
                  :backend-errors="validation.getErrorMessages(inputField.name)"
                />
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-btn color="success" type="submit">保存</v-btn>
              </v-col>
            </v-row>
          </v-container>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Component, Prop, PropSync, Watch } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import { ValidationObserver } from 'vee-validate'
import { MutationOptions } from 'apollo-client'
import { DocumentNode } from 'graphql'
import { isApolloError } from 'apollo-client/errors/ApolloError'
import Validation from '~/validation/validation'

export interface inputField {
  name: string
}

// @ts-ignore
@Component({})
export default abstract class AbstractMasterForm<
  Model extends { id: string },
  ModelInput
> extends Vue {
  protected abstract readonly validation: Validation
  protected abstract readonly inputFields: inputField[]
  protected abstract readonly title: string
  protected abstract readonly createMutation: DocumentNode
  protected abstract readonly updateMutation: DocumentNode

  protected abstract input: ModelInput

  @PropSync('isOpen', { type: Boolean, required: true })
  protected isOpenSync!: Boolean

  @Prop({
    type: Object as PropType<Model>,
    required: true,
  })
  protected model!: Model

  @Prop({ type: String, required: true })
  protected teamId!: String

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  @Watch('model', { immediate: true })
  private onModel(): void {
    const input: any = { team_id: this.teamId }
    this.inputFields.forEach((value) => {
      input[value.name] = (this.model as any)[value.name]
    })

    this.input = input as ModelInput
  }

  private onSubmit(): Promise<void> {
    const res = this.$apollo.mutate(this.mutateOption)

    return res.then(() => {
      this.$toast.success('保存しました')
      this.isOpenSync = false
      this.$emit('saved')
    })
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      await this.onSubmit().catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
    }
  }

  private get isCreate(): boolean {
    return !this.model.id
  }

  private get mutateOption(): MutationOptions {
    if (this.isCreate) {
      return {
        mutation: this.createMutation,
        variables: {
          input: this.input,
        },
      }
    }
    return {
      mutation: this.updateMutation,
      variables: {
        id: this.model.id,
        input: this.input,
      },
    }
  }
}
</script>
