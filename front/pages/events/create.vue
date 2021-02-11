<template>
  <event-form :event="event" :event-dates="eventDates" @submit="submit">
    <v-btn color="primary" @click="addDate">日程追加</v-btn>
  </event-form>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import {
  EventInput,
  EventDateInput,
  CreateEventMutation,
} from '~/apollo/graphql'
import EventForm from '~/components/events/EventForm.vue'

@Component({
  components: {
    EventForm,
  },
})
export default class CreateEvent extends Vue {
  private eventDates: EventDateInput[] = []

  private event: EventInput = {
    name: '',
    event_dates: this.eventDates,
  }

  private addDate(): void {
    this.eventDates.push({
      name: '',
      date: '',
      is_production_day: true,
    })
  }

  private submit(): void {
    const res = this.$apollo.mutate({
      mutation: CreateEventMutation,
      variables: {
        input: this.event,
      },
    })
    res
      .then(() => {
        // TODO: リダイレクト処理をかける
      })
      .catch(() => {
        // TODO: バリデーション失敗時にはエラーが出るようにする
      })
  }

  private created() {
    this.addDate()
  }
}
</script>
