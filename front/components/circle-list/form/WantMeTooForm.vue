<template>
  <validation-observer ref="validationObserver" tag="form">
    <want-me-too-form-input
      v-model="input"
      :validation="validation"
      :team-id="teamId"
      :circle-product="circleProduct"
    />

    <v-row dense>
      <v-col cols="12">
        <v-btn color="success" @click="submit">登録</v-btn>
        <v-btn @click="$emit('canceled')">キャンセル</v-btn>
      </v-col>
    </v-row>
  </validation-observer>
</template>

<script lang="ts">
import { Prop, Component, Watch } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import WantMeTooFormInput from './want-me-to-form/WantMeToFormInput.vue'
import AbstractForm from '~/components/form/AbstractForm.vue'
import {
  CircleProduct,
  WantCircleProduct,
  WantCircleProductInput,
  WantMeTooCircleProductMutation,
} from '~/apollo/graphql'
import { CreateWantCircleProductInputValidation } from '~/validation/validations'

const initialWantCircleProductInput: WantCircleProductInput = {
  quantity: 1,
  want_priority_id: '',
}

@Component({
  components: {
    WantMeTooFormInput,
  },
})
export default class WantMeTooForm extends AbstractForm<CreateWantCircleProductInputValidation> {
  protected validation: CreateWantCircleProductInputValidation =
    new CreateWantCircleProductInputValidation()

  private input: WantCircleProductInput = { ...initialWantCircleProductInput }

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

  protected async mutate(): Promise<any> {
    const variables = {
      input: {
        ...this.input,
        join_event_id: this.joinEventId,
        circle_product_id: this.circleProduct.id,
      },
    }

    return await this.$apollo.mutate({
      mutation: WantMeTooCircleProductMutation,
      variables,
    })
  }

  protected afterMutate(wantCircleProduct: WantCircleProduct): void {
    this.$emit('saved', { wantCircleProduct })
  }
}
</script>
