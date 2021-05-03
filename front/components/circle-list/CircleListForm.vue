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
        <circle-product-form />
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

  @Prop({ type: String })
  private careAboutCircleId!: String | null

  private isEditCircle: boolean = true

  private circleId: String | null = null

  private circlePlacement: CirclePlacement | null = null

  private get circle(): Circle {
    return this.circlePlacement?.circle ?? this.nullCircle
  }

  private readonly nullCircle: Circle = {
    id: '',
    name: '',
  }

  private onSavedCircle({ circle }: any) {
    const prevCircleId = this.circleId
    this.circleId = circle.id
    if (prevCircleId === circle.id) {
      this.$apollo.queries.circlePlacement.refetch()
    }
    this.isEditCircle = false
  }

  private editCircle() {
    this.isEditCircle = true
  }
}
</script>
