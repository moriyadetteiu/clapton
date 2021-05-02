<template>
  <validation-observer ref="validationObserver" tag="v-form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="circleInput.name"
            :validation="validation.getItem('circle.name')"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="12">
          <v-text-field
            v-model="circleInput.kana"
            placeholder="サークルの読み方"
          />
        </v-col>
      </v-row>

      <v-row dense>
        <v-col cols="4">
          <v-validate-select
            v-model="circlePlacementInput.event_date_id"
            :items="eventDates"
            item-text="name"
            item-value="id"
            :validation="validation.getItem('placement.event_date_id')"
          />
        </v-col>
        <v-col cols="2">
          <v-validate-select
            v-model="circlePlacementInput.hole"
            :items="holes"
            item-text="name"
            item-value="name"
            :validation="validation.getItem('placement.hole')"
          />
        </v-col>
        <v-col cols="2">
          <v-validate-text-field
            v-model="circlePlacementInput.line"
            :validation="validation.getItem('placement.line')"
            placeholder="A"
          />
        </v-col>
        <v-col cols="2">
          <v-validate-text-field
            v-model.number="circlePlacementInput.number"
            :validation="validation.getItem('placement.number')"
            type="number"
            placeholder="10"
          />
        </v-col>
        <v-col cols="2">
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

      <v-row dense>
        <v-col cols="12">
          <v-btn color="success" @click="submit">登録</v-btn>
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
import {
  CreateCircleParticipatingInEventInput,
  CreateCircleParticipatingInEventMutation,
  UpdateCircleParticipatingInEventMutation,
  CreateCareAboutCircleMutation,
  CircleInput,
  CirclePlacementInput,
  EventDate,
  EventWithDateQuery,
  CirclePlacement,
  CirclePlacementClassification,
  CirclePlacementClassificationsQuery,
  CareAboutCircleInput,
} from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'

// TODO: ほかでも使うようならモジュール化、ちゃんと理解する
//       https://qiita.com/mizchi/items/5c359fb5b5e921a7d55f
type Draft<T, D extends keyof T> = {
  [K in keyof T]: K extends D ? T[K] | null : T[K]
}

type DraftCirclePlacementInput = Draft<CirclePlacementInput, 'number'>

const initialCirclePlacementInput: DraftCirclePlacementInput = {
  event_date_id: '',
  hole: '東',
  line: '',
  number: null,
  a_or_b: 'a',
  circle_placement_classification_id: '',
}

const initialCircleInput: CircleInput = {
  name: '',
  kana: '',
}

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
export default class CircleForm extends Vue {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: string

  @Prop({ type: Object as PropType<CirclePlacement> })
  private circlePlacement?: CirclePlacement

  private validation: CreateCircleParticipatingInEventInputValidation = new CreateCircleParticipatingInEventInputValidation()

  private circleInput: CircleInput = { ...initialCircleInput }

  private circlePlacementInput: DraftCirclePlacementInput = {
    ...initialCirclePlacementInput,
  }

  private eventDates: EventDate[] = []

  // TODO: イベントごとにホールのマスタを変更できるようにする？
  private holes: { name: string }[] = [
    { name: '東' },
    { name: '西' },
    { name: '南' },
  ]

  private aOrB: { name: string }[] = [{ name: 'a' }, { name: 'b' }]

  private circlePlacementClassifications: CirclePlacementClassification[] = []

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  @Watch('circlePlacement', { immediate: true })
  private onUpdateCirclePlacement(): void {
    if (!this.circlePlacement) {
      this.circlePlacementInput = { ...initialCirclePlacementInput }
      this.circleInput = { ...initialCircleInput }
      return
    }
    const circlePlacementInput = {}
    Object.keys(initialCirclePlacementInput).forEach((key) => {
      ;(circlePlacementInput as any)[key] = (this.circlePlacement as any)[key]
    })
    this.circlePlacementInput = circlePlacementInput as CirclePlacementInput
    const circleInput = {}
    Object.keys(initialCircleInput).forEach((key) => {
      ;(circleInput as any)[key] = (this.circlePlacement?.circle as any)[key]
    })
    this.circleInput = circleInput as CircleInput
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const postMethod: Function = this.circlePlacement?.circle?.id
        ? this.updateCircle
        : this.createCircle
      const circle = await postMethod()

      this.$toast.success('保存しました')
      this.$emit('saved', { circle })
    }
  }

  private async createCircle() {
    const input: CreateCircleParticipatingInEventInput = {
      circle: this.circleInput,
      placement: this.circlePlacementInput as CirclePlacementInput,
    }

    const circle = await this.$apollo
      .mutate({
        mutation: CreateCircleParticipatingInEventMutation,
        variables: { input },
      })
      .then((res) => res.data.createCircleParticipatingInEvent)
      .catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })

    const careAboutCircleInput: CareAboutCircleInput = {
      join_event_id: this.joinEventId,
      circle_id: circle.id,
    }
    await this.$apollo
      .mutate({
        mutation: CreateCareAboutCircleMutation,
        variables: { input: careAboutCircleInput },
      })
      .then((res) => res.data.createCareAboutCircle)
      .catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
    return circle
  }

  private async updateCircle() {
    const input: CreateCircleParticipatingInEventInput = {
      circle: this.circleInput,
      placement: this.circlePlacementInput as CirclePlacementInput,
    }

    const id = this.circlePlacement!.circle!.id

    return await this.$apollo
      .mutate({
        mutation: UpdateCircleParticipatingInEventMutation,
        variables: { id, input },
      })
      .then((res) => {
        return res.data.updateCircleParticipatingInEvent
      })
      .catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
  }
}
</script>
