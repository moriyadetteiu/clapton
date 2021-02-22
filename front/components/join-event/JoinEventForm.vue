<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>参加情報編集</v-card-title>
      <v-card-text>
        <validation-observer
          ref="validationObserver"
          tag="v-form"
          @submit.prevent="submit"
        >
          <v-container>
            <v-row v-for="eventDate in eventDates" :key="eventDate.id">
              <v-col cols="4" class="font-weight-bold">
                {{ eventDate.name }}
              </v-col>
              <v-col cols="8">
                <validation-provider
                  v-slot="{ errors }"
                  :name="
                    validation.getItem('join_event_dates.*.is_join').attribute
                  "
                  :rules="
                    validation.getItem('join_event_dates.*.is_join').rules
                  "
                >
                  <v-switch
                    v-model="joinEventDateInputs[eventDate.id].is_join"
                    :error-messages="errors"
                  />
                </validation-provider>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-btn color="success" type="submit">保存</v-btn>
              </v-col>
            </v-row>
          </v-container>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import 'vue-apollo'
import { PropType } from 'vue'
import { Vue, Component, Prop, PropSync, Watch } from 'nuxt-property-decorator'
import { ValidationObserver } from 'vee-validate'
import { MutationOptions } from 'apollo-client'
import { isApolloError } from 'apollo-client/errors/ApolloError'
import {
  EventDate,
  JoinEventInput,
  JoinEventDateInput,
  JoinEventDate,
  CreateJoinEventMutation,
  UpdateJoinEventMutation,
} from '~/apollo/graphql'
import Validation from '~/validation/validation'
import {
  CreateJoinEventInputValidation,
  UpdateJoinEventInputValidation,
} from '~/validation/validations'

@Component
export default class JoinEventForm extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Prop({ type: Array as PropType<EventDate[]>, required: true })
  private eventDates!: EventDate[]

  @Prop({ type: Array as PropType<JoinEventDate[]> })
  private joinEventDates!: JoinEventDate[]

  @Prop({ type: String, required: true })
  private teamId!: string

  @Prop({ type: String, required: true })
  private eventId!: string

  @Prop({ type: String, required: true })
  private joinEventId!: string

  private joinEventDateInputs: { [key: string]: JoinEventDateInput } = {}

  private validation: Validation = new CreateJoinEventInputValidation()

  @Watch('joinEventDates', { immediate: true })
  private updateJoinEventDates() {
    let inputs: { [key: string]: JoinEventDateInput } = {}
    this.eventDates.forEach((eventDate) => {
      const joinEventDate: JoinEventDate | undefined = this.joinEventDates
        ? this.joinEventDates.find(
            (joinEventDate) => joinEventDate.eventDate?.id === eventDate.id
          )
        : undefined

      const input: JoinEventDateInput = {
        event_date_id: eventDate.id,
        is_join: joinEventDate ? Boolean(joinEventDate.is_join) : true,
      }

      inputs = {
        ...inputs,
        [eventDate.id]: input,
      }
    })
    this.joinEventDateInputs = inputs
  }

  @Watch('joinEventId')
  updateJoinEventId() {
    this.validation = this.isCreate
      ? new CreateJoinEventInputValidation()
      : new UpdateJoinEventInputValidation()
  }

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      await this.$apollo
        .mutate(this.mutationOptions)
        .catch((error) => {
          if (isApolloError(error)) {
            this.$toasted.global.validationError()
            this.validation.setBackendErrorsFromAppolo(error)
          }
        })
        .then(() => {
          this.$toast.success('保存しました')
          this.isOpenSync = false
          this.$emit('saved')
        })
    }
  }

  private get isCreate(): boolean {
    return !this.joinEventId
  }

  private get mutationOptions(): MutationOptions {
    const joinEventDateInputs: JoinEventDateInput[] = Object.values(
      this.joinEventDateInputs
    )

    if (this.isCreate) {
      const input: JoinEventInput = {
        team_id: this.teamId,
        event_id: this.eventId,
        join_event_dates: joinEventDateInputs,
      }
      return {
        mutation: CreateJoinEventMutation,
        variables: {
          input,
        },
      }
    }
    const input: JoinEventInput = {
      join_event_dates: joinEventDateInputs,
    }
    const id = this.joinEventId

    return {
      mutation: UpdateJoinEventMutation,
      variables: {
        id,
        input,
      },
    }
  }
}
</script>
