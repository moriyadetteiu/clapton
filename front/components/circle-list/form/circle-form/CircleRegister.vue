<template>
  <validation-observer ref="validationObserver" tag="form">
    <circle-form-input
      v-model="circleInput"
      :validation="validation"
      :disabled="disabledCircleForm"
      class="pb-0"
    />
    <circle-placement-form-input
      v-model="circlePlacementInput"
      :validation="validation"
      :event-id="eventId"
      :team-id="teamId"
      class="pt-0 pb-0"
    />
    <care-about-circle-form-input
      v-model="careAboutCircleMemoInput"
      class="pt-0 pb-0"
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
import { Prop, Component, Watch } from 'nuxt-property-decorator'
import { ApolloError } from 'apollo-client/errors/ApolloError'
import { GraphQLError } from 'graphql'
import CircleFormInput from './CircleFormInput.vue'
import CirclePlacementFormInput, {
  DraftCirclePlacementInput,
} from './CirclePlacementFormInput.vue'
import CareAboutCircleFormInput, {
  CareAboutCircleMemoInput,
} from './CareAboutCircleFormInput.vue'
import {
  CreateCircleParticipatingInEventInput,
  CreateCircleParticipatingInEventMutation,
  CreateCareAboutCircleMutation,
  CircleInput,
  CirclePlacementInput,
  CareAboutCircleInput,
  CirclePlacement,
  CircleQuery,
  Circle,
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

export const initialCareAboutCircleMemoInput: CareAboutCircleMemoInput = {
  memo: '',
}

@Component({
  components: {
    CircleFormInput,
    CirclePlacementFormInput,
    CareAboutCircleFormInput,
  },
  apollo: {
    circle: {
      query: CircleQuery,
      variables() {
        const id = this.circleId
        return { id }
      },
      result(result: any) {
        const circle: Circle | null = result?.data?.circle
        if (!circle) {
          return
        }

        this.circleInput = {
          id: circle.id,
          name: circle.name,
          kana: circle.kana,
        }
      },
      skip(): boolean {
        return !this.circleId
      },
    },
  },
})
export default class CircleRegister extends AbstractForm<CreateCircleParticipatingInEventInputValidation> {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String, required: true })
  private joinEventId!: string

  @Prop({ type: String })
  private circleId!: string | null

  protected validation: CreateCircleParticipatingInEventInputValidation =
    new CreateCircleParticipatingInEventInputValidation()

  private circleInput: CircleInput = { ...initialCircleInput }

  private circlePlacementInput: DraftCirclePlacementInput = {
    ...initialCirclePlacementInput,
  }

  private careAboutCircleMemoInput: CareAboutCircleMemoInput = {
    ...initialCareAboutCircleMemoInput,
  }

  private force: boolean = false

  private get disabledCircleForm(): boolean {
    return !!this.circleId
  }

  @Watch('circleId')
  private onUpdateCircleId(): void {
    if (!this.circleId) {
      this.circleInput = { ...initialCircleInput }
    }
  }

  protected async mutate(): Promise<any> {
    const input: CreateCircleParticipatingInEventInput = {
      circle: this.circleInput,
      placement: this.circlePlacementInput as CirclePlacementInput,
    }
    const force = this.force

    return await this.$apollo
      .mutate({
        mutation: CreateCircleParticipatingInEventMutation,
        variables: { input, force },
      })
      .then((res) => res.data.createCircleParticipatingInEvent)
  }

  protected async afterMutate(circlePlacement: CirclePlacement) {
    await this.createCareAboutCircle(circlePlacement)
    const circle = circlePlacement.circle
    this.$emit('saved', { circle })
  }

  protected async handleGraphQLError(
    graphQLError: GraphQLError,
    apolloError: ApolloError
  ) {
    if (graphQLError.extensions.category === 'validation') {
      this.handleValidationError(apolloError)
    }
    if (graphQLError.extensions.category === 'conflictCircle') {
      if (await this.confirmForceCreate(graphQLError)) {
        this.force = true
        try {
          await this.submit()
        } finally {
          this.force = false
        }
      }
    }
  }

  private async confirmForceCreate(
    graphQLError: GraphQLError
  ): Promise<Boolean> {
    const conflictCirclesMessage: string = graphQLError.extensions.conflicts
      .map(
        (circlePlacement: any) =>
          `${circlePlacement.circle.name}（${circlePlacement.formatted_placement}）`
      )
      .join(`\n`)

    return await this.$confirmDialog.confirm(
      `以下のサークルと競合しています。再度確認してください。（実行を選ぶと登録します。）

      ${conflictCirclesMessage}
      `
    )
  }

  private async createCareAboutCircle(circlePlacement: CirclePlacement) {
    const careAboutCircleInput: CareAboutCircleInput = {
      join_event_id: this.joinEventId,
      circle_placement_id: circlePlacement.id,
      memo: this.careAboutCircleMemoInput.memo,
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
