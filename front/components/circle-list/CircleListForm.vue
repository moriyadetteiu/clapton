<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>
        <template v-if="isEditCircle">サークルリスト編集</template>
        <template v-else>
          {{ circlePlacement ? circlePlacement.formatted_placement : '' }}
          {{ circle.name }}
          <v-spacer />
          <v-btn color="success" @click="editCircle">編集</v-btn>
        </template>
      </v-card-title>
      <v-card-text>
        <circle-form
          v-if="isEditCircle"
          :event-id="eventId"
          :team-id="teamId"
          :join-event-id="joinEventId"
          :circle-placement="circlePlacement"
          @saved="onSavedCircle"
        />
        <template v-if="circlePlacement && !isEditCircle">
          <template v-if="!isEditCircleProduct">
            <v-row
              v-for="circleProduct in circleProducts"
              :key="circleProduct.id"
            >
              <v-col cols="12">
                {{ circleProduct.circleProductClassification.name }}
                {{ circleProduct.name }}
                <v-btn @click="editCircleProduct(circleProduct)">編集</v-btn>
                <v-btn @click="deleteCircleProduct(circleProduct)">削除</v-btn>
              </v-col>
            </v-row>
          </template>
          <circle-product-form
            v-if="isEditCircleProduct"
            :team-id="teamId"
            :circle-placement-id="circlePlacement.id"
            :circle-product="edittingCircleProduct"
            :join-event-id="joinEventId"
            @saved="onSavedCircleProduct"
            @canceled="cancelCircleProduct"
          />
          <v-btn
            v-if="!isEditCircleProduct"
            color="register"
            @click="addCircleProduct"
          >
            頒布物追加
          </v-btn>
        </template>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Prop, PropSync, Component } from 'nuxt-property-decorator'
import CircleForm from './form/CircleForm.vue'
import CircleProductForm from './form/CircleProductForm.vue'
import {
  Circle,
  CirclePlacement,
  CirclePlacementInEventQuery,
  CircleProduct,
  DeleteCircleProductMutation,
} from '~/apollo/graphql'

@Component({
  components: {
    CircleForm,
    CircleProductForm,
  },
  apollo: {
    circlePlacement: {
      query: CirclePlacementInEventQuery,
      variables() {
        const eventId = this.eventId
        const circleId = this.circleId

        return { eventId, circleId }
      },
      skip() {
        return !this.circleId || !this.eventId
      },
      update(data) {
        return data.circlePlacementInEvent
      },
    },
  },
})
export default class CircleListForm extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: String | null

  private isEditCircle: boolean = true

  private circleId: String | null = null

  private circlePlacement: CirclePlacement | null = null

  private isEditCircleProduct: boolean = false

  private edittingCircleProduct: CircleProduct | null = null

  private get circle(): Circle {
    return this.circlePlacement?.circle ?? this.nullCircle
  }

  private get circleProducts(): CircleProduct[] {
    return this.circlePlacement?.circleProducts ?? []
  }

  private readonly nullCircle: Circle = {
    id: '',
    name: '',
  }

  private onSavedCircle({ circle }: any): void {
    const prevCircleId = this.circleId
    this.circleId = circle.id
    if (prevCircleId === circle.id) {
      this.$apollo.queries.circlePlacement.refetch()
    }
    this.isEditCircle = false
  }

  private editCircle(): void {
    this.cancelCircleProduct()
    this.isEditCircle = true
  }

  private addCircleProduct(): void {
    this.edittingCircleProduct = null
    this.isEditCircleProduct = true
  }

  private editCircleProduct(circleProduct: CircleProduct): void {
    this.edittingCircleProduct = { ...circleProduct }
    this.isEditCircleProduct = true
  }

  private async deleteCircleProduct(circleProduct: CircleProduct) {
    await this.$apollo
      .mutate({
        mutation: DeleteCircleProductMutation,
        variables: { id: circleProduct.id },
      })
      .catch(() => this.$toast.error('削除に失敗しました'))

    this.$toast.success('削除しました')
    this.$apollo.queries.circlePlacement.refetch()
  }

  private onSavedCircleProduct(): void {
    this.$apollo.queries.circlePlacement.refetch()
    this.isEditCircleProduct = false
  }

  private cancelCircleProduct(): void {
    this.isEditCircleProduct = false
    this.edittingCircleProduct = null
  }
}
</script>
