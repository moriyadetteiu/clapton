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
import { Prop, Component, Watch } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import { isApolloError } from 'apollo-client/errors/ApolloError'
import CircleFormInput from './CircleFormInput.vue'
import CirclePlacementFormInput, {
  DraftCirclePlacementInput,
} from './CirclePlacementFormInput.vue'
import {
  initialCirclePlacementInput,
  initialCircleInput,
} from './CircleRegister.vue'
import {
  CreateCircleParticipatingInEventInput,
  UpdateCircleParticipatingInEventMutation,
  Circle,
  CircleInput,
  CirclePlacementInput,
  CirclePlacement,
} from '~/apollo/graphql'
import { CreateCircleParticipatingInEventInputValidation } from '~/validation/validations'
import AbstractForm from '~/components/form/AbstractForm.vue'

@Component({
  components: { CircleFormInput, CirclePlacementFormInput },
})
export default class CircleEditor extends AbstractForm<CreateCircleParticipatingInEventInputValidation> {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: string

  @Prop({ type: Object as PropType<CirclePlacement>, required: true })
  private circlePlacement!: CirclePlacement

  protected validation: CreateCircleParticipatingInEventInputValidation =
    new CreateCircleParticipatingInEventInputValidation()

  private circleInput: CircleInput = { ...initialCircleInput }

  private circlePlacementInput: DraftCirclePlacementInput = {
    ...initialCirclePlacementInput,
  }

  @Watch('circlePlacement', { immediate: true })
  private onUpdateCirclePlacement(): void {
    this.circlePlacementInput = this.migrateModelToInput(
      this.circlePlacementInput,
      this.circlePlacement
    )
    this.circleInput = this.migrateModelToInput(
      this.circleInput,
      this.circlePlacement.circle
    )
  }

  protected async mutate(): Promise<any> {
    const input: CreateCircleParticipatingInEventInput = {
      circle: this.circleInput,
      placement: this.circlePlacementInput as CirclePlacementInput,
    }

    const id = this.circlePlacement.circle!.id

    return await this.$apollo
      .mutate({
        mutation: UpdateCircleParticipatingInEventMutation,
        variables: { id, input },
      })
      .then((res) => {
        return res.data.updateCircleParticipatingInEvent.circle
      })
  }

  protected afterMutate(circle: Circle) {
    this.$emit('saved', { circle })
  }

  protected handleError(error: any): void {
    if (isApolloError(error)) {
      const errorExtensions = error.graphQLErrors[0].extensions
      if (errorExtensions.category === 'updateDenied') {
        this.$toast.error(errorExtensions.message)
        return
      }

      this.$toasted.global.validationError()
      this.validation.setBackendErrorsFromAppolo(error)
    }
  }
}
</script>
