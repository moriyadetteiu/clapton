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
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import { Prop, Component } from 'nuxt-property-decorator'
import CircleFormInput from './CircleFormInput.vue'
import CirclePlacementFormInput, {
  DraftCirclePlacementInput,
} from './CirclePlacementFormInput.vue'
import {
  CreateCircleParticipatingInEventInput,
  CreateCircleParticipatingInEventMutation,
  CreateCareAboutCircleMutation,
  CircleInput,
  CirclePlacementInput,
  CareAboutCircleInput,
  CirclePlacement,
} from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'
import AbstractForm from '~/components/form/AbstractForm.vue'

export const initialCirclePlacementInput: DraftCirclePlacementInput = {
  event_date_id: '',
  hole: '東',
  line: '',
  number: null,
  a_or_b: 'a',
  circle_placement_classification_id: '',
}

export const initialCircleInput: CircleInput = {
  name: '',
  kana: '',
}

@Component({
  components: { CircleFormInput, CirclePlacementFormInput },
})
export default class CircleRegister extends AbstractForm<CreateCircleParticipatingInEventInputValidation> {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: string

  protected validation: CreateCircleParticipatingInEventInputValidation =
    new CreateCircleParticipatingInEventInputValidation()

  private circleInput: CircleInput = { ...initialCircleInput }

  private circlePlacementInput: DraftCirclePlacementInput = {
    ...initialCirclePlacementInput,
  }

  protected async mutate(): Promise<any> {
    const input: CreateCircleParticipatingInEventInput = {
      circle: this.circleInput,
      placement: this.circlePlacementInput as CirclePlacementInput,
    }

    return await this.$apollo
      .mutate({
        mutation: CreateCircleParticipatingInEventMutation,
        variables: { input },
      })
      .then((res) => res.data.createCircleParticipatingInEvent)
  }

  protected async afterMutate(circlePlacement: CirclePlacement) {
    await this.createCareAboutCircle(circlePlacement)
    const circle = circlePlacement.circle
    this.$emit('saved', { circle })
  }

  private async createCareAboutCircle(circlePlacement: CirclePlacement) {
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
        this.handleError(error)
      })
    return circlePlacement.circle
  }
}
</script>
