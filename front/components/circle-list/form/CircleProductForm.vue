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
  UpdateCircleProductMutation,
  WantCircleProductInput,
  WantPrioritiesQuery,
  WantPriority,
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
  care_about_circle_id: '',
  circle_product_id: '',
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

  @Prop({ type: Object as PropType<CircleProduct> })
  private circleProduct?: CircleProduct

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  @Watch('circleProduct', { immediate: true })
  private onUpdateCircleProduct(): void {
    if (!this.circleProduct) {
      this.input = { ...initialCircleProductInput }
      return
    }
    const input = {}
    Object.keys(initialCircleProductInput).forEach((key) => {
      ;(input as any)[key] = (this.circleProduct as any)[key]
    })
    this.input = input as CircleProductInput
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const mutationName = this.isCreate
        ? 'createCircleProductMutation'
        : 'updateCircleProductMutation'
      const circleProduct = await this.$apollo
        .mutate(this.mutateOption)
        .then((res) => res.data![mutationName])
        .catch((error) => {
          if (isApolloError(error)) {
            this.$toasted.global.validationError()
            this.validation.setBackendErrorsFromAppolo(error)
          }
        })
      this.$toast.success('保存しました')
      this.$emit('saved', { circleProduct })
    }
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
}
</script>
