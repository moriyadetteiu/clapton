<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>
        <template v-if="isEditCircle">サークルリスト登録</template>
        <template v-else>
          <favorite-button v-if="circleId" :circle-id="circleId" />
          {{ circlePlacement ? circlePlacement.formatted_placement : '' }}
          {{ circle.name }}
          <v-spacer />
          <edit-btn @click="editCircle" />
          <delete-btn v-if="myCareAboutCircle" @click="dontCareCircle" />
        </template>
      </v-card-title>
      <v-card-text>
        <component
          :is="formState.getComponentName()"
          v-bind="formState.getAttrs()"
          v-on="formState.getOn()"
        />
      </v-card-text>
      <v-card-actions v-if="formState.shouldDisplayActions()">
        <v-btn color="register" @click="addCircleProduct"> 頒布物追加 </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Prop, PropSync, Component, Watch } from 'nuxt-property-decorator'
import CircleForm from './form/CircleForm.vue'
import CircleProductForm from './form/CircleProductForm.vue'
import CircleProducts from './form/CircleProducts.vue'
import FormStateInterface from './form/states/FormStateInterface'
import WantMeTooForm from './form/WantMeTooForm.vue'
import CircleFormState from './form/states/CircleFormState'
import CircleProductFormState from './form/states/CircleProductFormState'
import CircleProductsState from './form/states/CircleProductsState'
import WantMeTooFormState from './form/states/WantMeTooFormState'
import FavoriteButton from '~/components/favorites/FavoriteButton.vue'
import {
  Circle,
  CirclePlacement,
  CirclePlacementInEventQuery,
  CircleProduct,
  CareAboutCircle,
  DontCareCircleMutation,
} from '~/apollo/graphql'

@Component({
  components: {
    CircleForm,
    CircleProductForm,
    CircleProducts,
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
    if (this.wantMeToCircleProduct) {
      return new WantMeTooFormState(
        {
          circleProduct: this.wantMeToCircleProduct!,
          joinEventId: this.joinEventId!,
          teamId: this.teamId,
        },
        {
          saved: this.onSavedWantMeTooForm,
          canceled: this.cancelWantMeTo,
        }
      )
    }

    if (this.isEditCircle) {
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

    if (this.isEditCircleProduct) {
      return new CircleProductFormState(
        {
          teamId: this.teamId,
          circlePlacementId: this.circlePlacement!.id,
          circleProduct: this.editingCircleProduct,
          joinEventId: this.joinEventId!,
        },
        {
          saved: this.onSavedCircleProduct,
          canceled: this.cancelCircleProduct,
        }
      )
    }

    return new CircleProductsState(
      {
        circleProducts: this.circleProducts,
      },
      {
        'delete-circle-product': this.onDeleteCircleProduct,
        'edit-circle-product': this.editCircleProduct,
        'want-me-too': this.onWantMeToo,
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

  private onDeleteCircleProduct(): void {
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
