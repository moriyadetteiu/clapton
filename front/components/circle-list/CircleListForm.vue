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
          <delete-btn v-if="this.myCareAboutCircle" @click="dontCareCircle" />
        </template>
      </v-card-title>
      <v-card-text>
        <want-me-too-form
          v-if="wantMeToCircleProduct"
          :circle-product="wantMeToCircleProduct"
          :join-event-id="joinEventId"
          :team-id="teamId"
          @saved="onSavedWantMeTooForm"
          @canceled="cancelWantMeTo"
        />
        <template v-else>
          <component
            :is="formState.getComponentName()"
            v-if="isEditCircle"
            v-bind="formState.getAttrs()"
            v-on="formState.getOn()"
          />
          <template v-if="circlePlacement && !isEditCircle">
            <template v-if="!isEditCircleProduct">
              <circle-product-row
                v-for="circleProduct in circleProducts"
                :key="circleProduct.id"
                :circle-product="circleProduct"
                @delete-circle-product="onDeleteCircleProduct"
                @edit-circle-product="editCircleProduct"
                @want-me-too="onWantMeToo"
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
        </template>
      </v-card-text>
      <v-card-actions
        v-if="
          circlePlacement &&
          !isEditCircle &&
          !isEditCircleProduct &&
          !wantMeToCircleProduct
        "
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
import WantMeTooForm from './form/WantMeTooForm.vue'
import CircleFormState from './form/states/CircleFormState'
import FavoriteButton from '~/components/favorites/FavoriteButton.vue'
import {
  Circle,
  CirclePlacement,
  CirclePlacementInEventQuery,
  CircleProduct,
  CareAboutCircle,
  DontCareCircleMutation,
} from '~/apollo/graphql'
import FormStateInterface from './form/states/FormStateInterface'

@Component({
  components: {
    CircleForm,
    CircleProductForm,
    CircleProductRow,
    FavoriteButton,
    WantMeTooForm,
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

  private wantMeToCircleProduct: CircleProduct | null = null

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

  private get myCareAboutCircle(): CareAboutCircle | null {
    return (
      this.circlePlacement?.careAboutCircles?.find(
        (careAboutCircle) => careAboutCircle.join_event_id === this.joinEventId
      ) ?? null
    )
  }

  private get formState(): FormStateInterface {
    return new CircleFormState(
      {
        eventId: this.eventId,
        teamId: this.teamId,
        joinEventId: this.joinEventId as String,
        circlePlacement: this.circlePlacement,
      },
      {
        saved: this.onSavedCircle,
      }
    )
  }

  @Watch('editingCircleId')
  private onUpdateEditingCircleId(editingCircleId: string | null): void {
    this.cancelCircleProduct()
    this.cancelWantMeTo()
    editingCircleId
      ? this.initializeDisplayCircle(editingCircleId)
      : this.clearForm()
  }

  private clearForm(): void {
    this.isEditCircle = true
    this.circleId = null
    this.circlePlacement = null
    this.wantMeToCircleProduct = null
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

  private async dontCareCircle() {
    const variables = {
      id: this.myCareAboutCircle?.id,
    }

    await this.$apollo.mutate({
      mutation: DontCareCircleMutation,
      variables,
    })

    this.isOpenSync = false
    this.$toast.success('マイリストからサークルを削除しました')
    this.$emit('saved')
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

  private onWantMeToo(circleProduct: CircleProduct): void {
    this.wantMeToCircleProduct = circleProduct
  }

  private onSavedWantMeTooForm(): void {
    this.onSavedCircleProduct()
    this.cancelCircleProduct()
    this.cancelWantMeTo()
  }

  private cancelWantMeTo(): void {
    this.wantMeToCircleProduct = null
  }
}
</script>
