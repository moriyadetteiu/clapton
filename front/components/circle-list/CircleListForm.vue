<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title class="pr-sm-6 pl-sm-6 pr-1 pl-1">
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
      <v-card-text class="pr-sm-6 pl-sm-6 pr-1 pl-1">
        <app-loading :is-loading="isLoading" />
        <component
          :is="formState.getComponentName()"
          v-bind="formState.getAttrs()"
          v-on="formState.getOn()"
          @saved="onSaved"
          @canceled="cancelEdit"
        />
      </v-card-text>
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
import AppLoading from '~/components/loading/AppLoading.vue'
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
    AppLoading,
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

  @PropSync('editingCircleId', { type: String, default: null })
  private circleId!: String | null

  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: String

  private isEditCircle: boolean = true

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
        {}
      )
    }

    if (this.isEditCircle) {
      return new CircleFormState(
        {
          eventId: this.eventId,
          teamId: this.teamId,
          joinEventId: this.joinEventId,
          circlePlacement: this.circlePlacement,
          circleId: this.circleId,
          careAboutCircle: this.myCareAboutCircle,
        },
        {}
      )
    }

    if (this.isEditCircleProduct) {
      return new CircleProductFormState(
        {
          teamId: this.teamId,
          circlePlacementId: this.circlePlacement!.id,
          circleProduct: this.editingCircleProduct,
          joinEventId: this.joinEventId,
        },
        {}
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
        'add-circle-product': this.addCircleProduct,
        'register-new-circle': this.initializeForm,
      }
    )
  }

  private get isLoading(): boolean {
    return this.$apollo.queries?.circlePlacement?.loading ?? false
  }

  @Watch('circleId')
  private onUpdateCircleId(): void {
    if (!this.circleId) {
      this.circlePlacement = null
    }
  }

  @Watch('circlePlacement')
  private onUpdateCirclePlacement(): void {
    this.cancelEdit()
  }

  private initializeForm(): void {
    this.circleId = null
    this.clearForm()
  }

  private clearForm(): void {
    this.isEditCircle = true
    this.circlePlacement = null
    this.wantMeToCircleProduct = null
  }

  private editCircle(): void {
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

    this.circleId = null
    this.onSaved()
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

  private cancelEdit(): void {
    this.isEditCircle = false
    this.isEditCircleProduct = false
    this.editingCircleProduct = null
    this.wantMeToCircleProduct = null

    if (!this.circlePlacement) {
      this.clearForm()
    }
  }

  private async onSaved(payload?: any): Promise<any> {
    if (payload?.circle?.id) {
      this.circleId = payload.circle.id
      // HACK: nextTickがないと、PropSyncでemitしたcircleIdが本コンポーネントのpropに伝達する前にほかの処理に行くため、必要
      await this.$nextTick()
    }

    this.$apollo.queries.circlePlacement.refetch()
    this.cancelEdit()
    this.$emit('saved')
  }

  private onWantMeToo(circleProduct: CircleProduct): void {
    this.wantMeToCircleProduct = circleProduct
  }
}
</script>

<style scoped>
/* stylelint-disable-next-line selector-class-pattern */
.v-card__title {
  flex-wrap: nowrap;
}
</style>
