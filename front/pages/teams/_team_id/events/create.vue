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
  head() {
    return {
      title: 'イベント追加',
    }
  },
  components: {
    EventForm,
  },
})
export default class CreateEvent extends Vue {
  private eventDates: EventDateInput[] = []

  private event: EventInput = {
    name: '',
    team_id: '', // eslint-disable-line camelcase
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
      refetchQueries: [
        'UnderwayEventsForJoinedTeamsQuery',
        'FinishedEventsForJoinedTeamsQuery',
      ],
    })

    res
      .then(() => {
        this.$toast.success('登録しました')
        this.$router.push(`/teams/${this.$route.params.team_id}`)
      })
      .catch(() => {
        // TODO: バリデーション失敗時にはエラーが出るようにする
      })
  }

  private created() {
    this.addDate()
  }

  private mounted() {
    this.event.team_id = this.$route.params.team_id
  }
}
</script>
