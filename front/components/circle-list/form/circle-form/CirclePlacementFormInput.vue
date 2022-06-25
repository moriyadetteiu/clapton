<template>
  <v-container>
    <v-row dense>
      <v-col cols="6" sm="4">
        <v-validate-select
          v-model="circlePlacementInput.event_date_id"
          :items="eventDates"
          item-text="name"
          item-value="id"
          :validation="validation.getItem('placement.event_date_id')"
        />
      </v-col>
      <v-col cols="6" sm="2">
        <v-validate-select
          v-model="circlePlacementInput.hole"
          :items="holes"
          item-text="name"
          item-value="name"
          :validation="validation.getItem('placement.hole')"
        />
      </v-col>
      <v-col cols="4" sm="2">
        <v-validate-text-field
          v-model="circlePlacementInput.line"
          :validation="validation.getItem('placement.line')"
          placeholder="A"
        />
      </v-col>
      <v-col cols="4" sm="2">
        <v-validate-text-field
          v-model.number="circlePlacementInput.number"
          :validation="validation.getItem('placement.number')"
          type="number"
          placeholder="10"
        />
      </v-col>
      <v-col cols="4" sm="2">
        <v-validate-select
          v-model="circlePlacementInput.a_or_b"
          :items="aOrB"
          item-text="name"
          item-value="name"
          :validation="validation.getItem('placement.a_or_b')"
        />
      </v-col>
    </v-row>
    <v-row dense>
      <v-col cols="12">
        <v-validate-select
          v-model="circlePlacementInput.circle_placement_classification_id"
          :items="circlePlacementClassifications"
          item-text="name"
          item-value="id"
          :validation="
            validation.getItem('placement.circle_placement_classification_id')
          "
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Vue, Prop, PropSync, Component } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import {
  CirclePlacementInput,
  EventDate,
  EventWithDateQuery,
  CirclePlacement,
  CirclePlacementClassification,
  CirclePlacementClassificationsQuery,
} from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'

// TODO: ほかでも使うようならモジュール化、ちゃんと理解する
//       https://qiita.com/mizchi/items/5c359fb5b5e921a7d55f
type Draft<T, D extends keyof T> = {
  [K in keyof T]: K extends D ? T[K] | null : T[K]
}

export type DraftCirclePlacementInput = Draft<CirclePlacementInput, 'number'>

@Component({
  apollo: {
    eventDates: {
      query: EventWithDateQuery,
      variables() {
        return { id: this.eventId }
      },
      update(data) {
        return data.event.eventDates
      },
    },
    circlePlacementClassifications: {
      query: CirclePlacementClassificationsQuery,
      variables() {
        return { teamId: this.teamId }
      },
    },
  },
})
export default class CirclePlacementFormInput extends Vue {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({
    type: Object as PropType<CreateCircleParticipatingInEventInputValidation>,
  })
  private validation!: CreateCircleParticipatingInEventInputValidation

  @PropSync('input', {
    type: Object as PropType<CirclePlacement>,
    required: true,
  })
  private circlePlacementInput!: DraftCirclePlacementInput

  private eventDates: EventDate[] = []

  // TODO: イベントごとにホールのマスタを変更できるようにする？
  private holes: { name: string }[] = [
    { name: '東' },
    { name: '西' },
    { name: '南' },
  ]

  private aOrB: { name: string }[] = [{ name: 'a' }, { name: 'b' }]

  private circlePlacementClassifications: CirclePlacementClassification[] = []
}
</script>
