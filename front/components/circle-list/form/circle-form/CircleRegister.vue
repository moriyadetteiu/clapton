<template>
  <validation-observer ref="validationObserver" tag="form">
    <circle-form-input :input.sync="circleInput" :validation="validation" />
    <circle-placement-form-input
      :input.sync="circlePlacementInput"
      :validation="validation"
      :event-id="eventId"
      :team-id="teamId"
    />
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-btn color="success" @click="submit">登録</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { ValidationObserver } from 'vee-validate'
import { isApolloError } from 'apollo-client/errors/ApolloError'
import CircleForm from './CircleFormInput.vue'
import CirclePlacementForm, {
  DraftCirclePlacementInput,
} from './CirclePlacementFormInput.vue'
import {
  CreateCircleParticipatingInEventInput,
  CreateCircleParticipatingInEventMutation,
  CreateCareAboutCircleMutation,
  CircleInput,
  CirclePlacementInput,
  CareAboutCircleInput,
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
export default class CircleRegister extends Vue {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: string

  private validation: CreateCircleParticipatingInEventInputValidation =
    new CreateCircleParticipatingInEventInputValidation()

  private circleInput: CircleInput = { ...initialCircleInput }

  private circlePlacementInput: DraftCirclePlacementInput = {
    ...initialCirclePlacementInput,
  }

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const circle = await this.createCircle()

      if (!circle) {
        return
      }

      this.$toast.success('保存しました')
      this.$emit('saved', { circle })
    }
  }

  private async createCircle() {
    const input: CreateCircleParticipatingInEventInput = {
      circle: this.circleInput,
      placement: this.circlePlacementInput as CirclePlacementInput,
    }

    const circlePlacement = await this.$apollo
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
      circle_placement_id: circlePlacement.id,
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
    return circlePlacement.circle
  }
}
</script>
