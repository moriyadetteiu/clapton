<template>
  <validation-observer ref="validationObserver" tag="form">
    <circle-form-input v-model="circleInput" :validation="validation" />
    <circle-placement-form-input
      v-model="circlePlacementInput"
      :validation="validation"
      :event-id="eventId"
      :team-id="teamId"
    />
    <v-container>
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
import CirclePlacementForm, {
  DraftCirclePlacementInput,
} from './CirclePlacementFormInput.vue'
import {
  CreateCircleParticipatingInEventInput,
  UpdateCircleParticipatingInEventMutation,
  CircleInput,
  CirclePlacementInput,
  CirclePlacement,
} from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'

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
  components: { CircleForm, CirclePlacementForm },
})
export default class CircleForm extends Vue {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: string

  @Prop({ type: Object as PropType<CirclePlacement>, required: true })
  private circlePlacement!: CirclePlacement

  private validation: CreateCircleParticipatingInEventInputValidation =
    new CreateCircleParticipatingInEventInputValidation()

  private circleInput: CircleInput = { ...initialCircleInput }

  private circlePlacementInput: DraftCirclePlacementInput = {
    ...initialCirclePlacementInput,
  }

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
      const circle = await this.updateCircle()

      if (!circle) {
        return
      }

      this.$toast.success('保存しました')
      this.$emit('saved', { circle })
    }
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
        return res.data.updateCircleParticipatingInEvent.circle
      })
      .catch((error) => {
        if (isApolloError(error)) {
          const errorExtensions = error.graphQLErrors[0].extensions
          if (errorExtensions.category === 'updateDenied') {
            this.$toast.error(errorExtensions.message)
            return
          }

          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
  }
}
</script>
