<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>
        <template v-if="isEditCircle">サークルリスト編集</template>
        <template v-else>
          <favorite-button v-if="circleId" :circle-id="circleId" />
          {{ circlePlacement ? circlePlacement.formatted_placement : '' }}
          {{ circle.name }}
          <v-spacer />
          <edit-btn @click="editCircle" />
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
            <circle-product-row
              v-for="circleProduct in circleProducts"
              :key="circleProduct.id"
              :circle-product="circleProduct"
              @delete-circle-product="onDeleteCircleProduct"
              @edit-circle-product="editCircleProduct"
            />
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
        </template>
      </v-card-text>
      <v-card-actions
        v-if="circlePlacement && !isEditCircle && !isEditCircleProduct"
      >
        <v-btn color="register" @click="addCircleProduct"> 頒布物追加 </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Prop, PropSync, Component, Watch } from 'nuxt-property-decorator'
import CircleForm from './form/CircleForm.vue'
import CircleProductForm from './form/CircleProductForm.vue'
import CircleProductRow from './form/CircleProductRow.vue'
import FavoriteButton from '~/components/favorites/FavoriteButton.vue'
import {
  Circle,
  CirclePlacement,
  CirclePlacementInEventQuery,
  CircleProduct,
} from '~/apollo/graphql'

@Component({
  components: {
    CircleForm,
    CircleProductForm,
    CircleProductRow,
    FavoriteButton,
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

  private onDeleteCircleProduct() {
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
