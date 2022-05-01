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
            :circle-product="editingCircleProduct"
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
import { Vue, Prop, PropSync, Component, Watch } from 'nuxt-property-decorator'
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

  @Prop({ type: String, default: null })
  private editingCircleId!: String | null

  private isEditCircle: boolean = true

  private circleId: String | null = null

  private circlePlacement: CirclePlacement | null = null

  private isEditCircleProduct: boolean = false

  private editingCircleProduct: CircleProduct | null = null

  private readonly nullCircle: Circle = {
    id: '',
    name: '',
  }

  private get circle(): Circle {
    return this.circlePlacement?.circle ?? this.nullCircle
  }

  private get circleProducts(): CircleProduct[] {
    return this.circlePlacement?.circleProducts ?? []
  }

  @Watch('editingCircleId')
  private onUpdateEditingCircleId(editingCircleId: string | null): void {
    this.cancelCircleProduct()
    editingCircleId
      ? this.initializeDisplayCircle(editingCircleId)
      : this.clearForm()
  }

  private clearForm(): void {
    this.isEditCircle = true
    this.circleId = null
    this.circlePlacement = null
  }

  private initializeDisplayCircle(editingCircleId: string): void {
    this.isEditCircle = false
    this.circleId = editingCircleId
  }

  private onSavedCircle({ circle }: any): void {
    const prevCircleId = this.circleId
    this.circleId = circle.id
    if (prevCircleId === circle.id) {
      this.$apollo.queries.circlePlacement.refetch()
    }
    this.isEditCircle = false
    this.$emit('saved')
  }

  private editCircle(): void {
    this.cancelCircleProduct()
    this.isEditCircle = true
  }

  private addCircleProduct(): void {
    this.editingCircleProduct = null
    this.isEditCircleProduct = true
  }

  private editCircleProduct(circleProduct: CircleProduct): void {
    this.editingCircleProduct = { ...circleProduct }
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
    this.$emit('saved')
  }

  private onSavedCircleProduct(): void {
    this.$apollo.queries.circlePlacement.refetch()
    this.isEditCircleProduct = false
    this.$emit('saved')
  }

  private cancelCircleProduct(): void {
    this.isEditCircleProduct = false
    this.editingCircleProduct = null
  }
}
</script>
