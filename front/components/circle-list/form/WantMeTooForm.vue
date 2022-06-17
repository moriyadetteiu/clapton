<template>
  <validation-observer ref="validationObserver" tag="form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          {{ circleProduct.name }}
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="input.quantity"
            type="number"
            :validation="validation.getItem('quantity')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-validate-select
            v-model="input.want_priority_id"
            :items="wantPriorities"
            item-text="name"
            item-value="id"
            :validation="validation.getItem('want_priority_id')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-btn color="success" @click="submit">登録</v-btn>
          <v-btn @click="$emit('canceled')">キャンセル</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import { Vue, Prop, Component, Watch } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import { ValidationObserver } from 'vee-validate'
import { isApolloError } from 'apollo-client/errors/ApolloError'
import {
  CircleProduct,
  WantCircleProductInput,
  WantPrioritiesQuery,
  WantPriority,
  WantMeTooCircleProductMutation,
} from '~/apollo/graphql'
import { CreateWantCircleProductInputValidation } from '~/validation/validations'

const initialWantCircleProductInput: WantCircleProductInput = {
  quantity: 1,
  want_priority_id: '',
}

@Component({
  apollo: {
    wantPriorities: {
      query: WantPrioritiesQuery,
      variables() {
        const teamId = this.teamId
        return { teamId }
      },
    },
  },
})
export default class WantMeTooForm extends Vue {
  private validation: CreateWantCircleProductInputValidation =
    new CreateWantCircleProductInputValidation()

  private input: WantCircleProductInput = { ...initialWantCircleProductInput }

  private wantPriorities: WantPriority[] = []

  @Prop({ type: String, required: true })
  private teamId!: string

  @Prop({ type: String, required: true })
  private joinEventId!: string

  @Prop({ type: Object as PropType<CircleProduct>, required: true })
  private circleProduct!: CircleProduct

  @Watch('circleProduct')
  private onUpdateCircleProduct(): void {
    this.input = { ...initialWantCircleProductInput }
  }

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const variables = {
        input: {
          ...this.input,
          join_event_id: this.joinEventId,
          circle_product_id: this.circleProduct.id,
        },
      }

      try {
        const wantCircleProduct = await this.$apollo.mutate({
          mutation: WantMeTooCircleProductMutation,
          variables,
        })

        this.$toast.success('保存しました')
        this.$emit('saved', { wantCircleProduct })
      } catch (error: any) {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      }
    }
  }
}
</script>
