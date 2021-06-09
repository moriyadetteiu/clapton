<template>
  <validation-observer ref="validationObserver" tag="v-form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-validate-select
            v-model="input.circle_product_classification_id"
            :items="circleProductClassifications"
            item-text="name"
            item-value="id"
            :validation="validation.getItem('circle_product_classification_id')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="input.name"
            :validation="validation.getItem('name')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="input.price"
            type="number"
            :validation="validation.getItem('price')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="wantInput.quantity"
            type="number"
            :validation="validation.getItem('quantity')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-validate-select
            v-model="wantInput.want_priority_id"
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
import { MutationOptions } from 'apollo-client'
import {
  CircleProduct,
  CircleProductInput,
  CircleProductClassification,
  CircleProductClassificationsQuery,
  CreateCircleProductMutation,
  CreateWantCircleProductMutation,
  UpdateCircleProductMutation,
  UpdateWantCircleProductMutation,
  WantCircleProductInput,
  WantPrioritiesQuery,
  WantPriority,
  WantCircleProduct,
} from '~/apollo/graphql'
import {
  CreateCircleProductInputValidation,
  CreateWantCircleProductInputValidation,
} from '~/validation/validations'

const validation = new CreateCircleProductInputValidation().merge(
  new CreateWantCircleProductInputValidation()
)

const initialCircleProductInput: CircleProductInput = {
  circle_product_classification_id: '',
  name: '',
  price: 0,
}

const initialWantCircleProductInput: WantCircleProductInput = {
  quantity: 1,
  want_priority_id: '',
}

@Component({
  apollo: {
    circleProductClassifications: {
      query: CircleProductClassificationsQuery,
      variables() {
        const teamId = this.teamId
        return { teamId }
      },
    },
    wantPriorities: {
      query: WantPrioritiesQuery,
      variables() {
        const teamId = this.teamId
        return { teamId }
      },
    },
  },
})
export default class CircleProductForm extends Vue {
  private validation: CreateCircleProductInputValidation = validation

  private input: CircleProductInput = { ...initialCircleProductInput }

  private wantInput: WantCircleProductInput = {
    ...initialWantCircleProductInput,
  }

  private circleProductClassifications: CircleProductClassification[] = []

  private wantPriorities: WantPriority[] = []

  @Prop({ type: String, required: true })
  private teamId!: string

  @Prop({ type: String, required: true })
  private circlePlacementId!: string

  @Prop({ type: String, required: true })
  private joinEventId!: string

  @Prop({ type: Object as PropType<CircleProduct> })
  private circleProduct?: CircleProduct

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  @Watch('circleProduct', { immediate: true })
  private onUpdateCircleProduct(): void {
    if (!this.circleProduct) {
      this.input = { ...initialCircleProductInput }
      this.wantInput = { ...initialWantCircleProductInput }
      return
    }
    const input = {}
    Object.keys(initialCircleProductInput).forEach((key) => {
      ;(input as any)[key] = (this.circleProduct as any)[key]
    })
    this.input = input as CircleProductInput

    const wantCircleProduct = this.wantCircleProduct
    if (!wantCircleProduct) {
      this.wantInput = { ...initialWantCircleProductInput }
      return
    }
    const wantInput = {}
    Object.keys(initialWantCircleProductInput).forEach((key) => {
      ;(wantInput as any)[key] = (wantCircleProduct as any)[key]
    })
    this.wantInput = wantInput as WantCircleProductInput
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const circleProduct = await this.submitCircleProduct()
      const wantCircleProduct = await this.submitWantCircleProduct(
        circleProduct.id
      )

      this.$toast.success('保存しました')
      this.$emit('saved', { circleProduct, wantCircleProduct })
    }
  }

  private async submitCircleProduct() {
    const mutationName = this.isCreate
      ? 'createCircleProduct'
      : 'updateCircleProduct'
    return await this.$apollo
      .mutate(this.mutateOption)
      .then((res) => res.data![mutationName])
      .catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
  }

  private async submitWantCircleProduct(circleProductId: string) {
    const wantMutationName = this.isCreate
      ? 'createWantCircleProduct'
      : 'updateWantCircleProduct'
    const wantMutateOption = this.wantMutateOption
    wantMutateOption.variables!.input = {
      ...wantMutateOption.variables!.input,
      circle_product_id: circleProductId,
    }
    const wantCircleProduct = await this.$apollo
      .mutate(this.wantMutateOption)
      .then((res) => res.data![wantMutationName])
      .catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
  }

  private get isCreate(): boolean {
    return !this.circleProduct?.id
  }

  private get mutateOption(): MutationOptions {
    const input = {
      ...this.input,
      circle_placement_id: this.circlePlacementId,
    }
    if (this.isCreate) {
      return {
        mutation: CreateCircleProductMutation,
        variables: { input },
      }
    }
    return {
      mutation: UpdateCircleProductMutation,
      variables: {
        id: this.circleProduct!.id,
        input,
      },
    }
  }

  private get wantMutateOption(): MutationOptions {
    const input = {
      ...this.wantInput,
    }
    if (this.isCreate) {
      input.join_event_id = this.joinEventId
      return {
        mutation: CreateWantCircleProductMutation,
        variables: { input },
      }
    }
    return {
      mutation: UpdateWantCircleProductMutation,
      variables: {
        id: this.wantCircleProduct!.id,
        input,
      },
    }
  }

  private get wantCircleProduct(): WantCircleProduct | null {
    if (!this.circleProduct || !this.circleProduct.wantCircleProducts) {
      return null
    }
    console.log(this.circleProduct!.wantCircleProducts)
    const targetWantCircleProduct = this.circleProduct!.wantCircleProducts!.find(
      (wantCircleProduct) =>
        wantCircleProduct!.careAboutCircle.join_event_id === this.joinEventId
    )
    if (!targetWantCircleProduct) {
      return null
    }
    return targetWantCircleProduct
  }
}
</script>
