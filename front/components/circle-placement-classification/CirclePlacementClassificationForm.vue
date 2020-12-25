<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>配置分類</v-card-title>
      <v-card-text>
        <validation-observer
          ref="validationObserver"
          tag="v-form"
          @submit.prevent="submit"
        >
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-validate-text-field
                  v-model="input.name"
                  outlined
                  :validation="validation.getItem('name')"
                  :backend-errors="validation.getErrorMessages('name')"
                />
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-validate-text-field
                  v-model="input.cost"
                  outlined
                  :validation="validation.getItem('cost')"
                  :backend-errors="validation.getErrorMessages('cost')"
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
import { isApolloError } from 'apollo-client/errors/ApolloError'
import { Vue, Component, Prop, PropSync, Watch } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import { ValidationObserver } from 'vee-validate'
import {
  CreateCirclePlacementClassificationMutation,
  CirclePlacementClassification,
  CirclePlacementClassificationInput,
} from '~/apollo/graphql'
import { CreateCirclePlacementClassificationInputValidation } from '~/validation/validations'

@Component({})
export default class CirclePlacementClassificationForm extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Prop({
    type: Object as PropType<CirclePlacementClassification>,
    required: true,
  })
  private circlePlacementClassification!: CirclePlacementClassification

  @Prop({ type: String, required: true })
  private teamId!: String

  private input: CirclePlacementClassificationInput = {
    team_id: '',
    name: '',
    cost: 0,
  }

  private validation: CreateCirclePlacementClassificationInputValidation = new CreateCirclePlacementClassificationInputValidation()

  @Watch('circlePlacementClassification', { immediate: true })
  private onUpdateCirclePlacementClassification(): void {
    this.input = {
      name: this.circlePlacementClassification.name,
      cost: this.circlePlacementClassification.cost,
      team_id: this.teamId,
    } as CirclePlacementClassificationInput
  }

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private onSubmit(): Promise<void> {
    const res = this.$apollo.mutate({
      mutation: CreateCirclePlacementClassificationMutation,
      variables: {
        input: this.input,
      },
    })

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
}
</script>
