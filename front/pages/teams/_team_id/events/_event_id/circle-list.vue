<template>
  <v-container>
    <join-event-form
      :is-open.sync="isOpenJoinEventForm"
      :event-dates="event.eventDates"
      :join-event-dates="joinEvent ? joinEvent.joinEventDates : []"
      :join-event-id="joinEvent ? joinEvent.id : ''"
      :team-id="$route.params.team_id"
      :event-id="$route.params.event_id"
      @saved="onSavedJoinEvent"
    />
    <v-expansion-panels accordion>
      <v-expansion-panel>
        <v-expansion-panel-header>参加情報 </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-btn color="register" @click="openJoinEventForm">登録</v-btn>
          <v-row v-for="eventDate in event.eventDates" :key="eventDate.id">
            <v-col cols="4" class="font-weight-bold">
              {{ eventDate.name }}
            </v-col>
            <v-col cols="8">{{ isJoin(eventDate) ? '参加' : '不参加' }}</v-col>
          </v-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </v-container>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import {
  Event,
  EventDate,
  JoinEvent,
  User,
  EventWithDateQuery,
  FindJoinEventWithDateQuery,
  MeQuery,
} from '~/apollo/graphql'
import JoinEventForm from '~/components/join-event/JoinEventForm.vue'

@Component({
  components: {
    JoinEventForm,
  },
  apollo: {
    event: {
      query: EventWithDateQuery,
      variables() {
        const eventId: string = this.$route.params.event_id
        return { id: eventId }
      },
    },
    // TODO: 全体でログイン情報は共有する
    user: {
      query: MeQuery,
      update(data) {
        return data.me
      },
    },
    joinEvent: {
      query: FindJoinEventWithDateQuery,
      variables() {
        const user = this.user
        const teamId: string = this.$route.params.team_id
        const eventId: string = this.$route.params.event_id
        return {
          user_id: user.id,
          team_id: teamId,
          event_id: eventId,
        }
      },
      skip() {
        return this.user.id === ''
      },
      update(data) {
        return data.findJoinEvent
      },
    },
  },
})
export default class CircleListPage extends Vue {
  private event: Event = {
    id: '',
    name: '',
    eventDates: [],
  }

  private isOpenJoinEventForm: Boolean = false

  private user: User = {
    id: '',
    name: '',
  }

  private joinEvent: JoinEvent | null = null

  private isJoin(eventDate: EventDate): boolean {
    if (!this.joinEvent || !this.joinEvent.joinEventDates) {
      return false
    }

    const joinEventDate = this.joinEvent.joinEventDates.find(
      (joinEventDate) => joinEventDate?.eventDate?.id === eventDate.id
    )
    if (!joinEventDate) {
      return false
    }

    return joinEventDate.is_join || false
  }

  private openJoinEventForm() {
    this.isOpenJoinEventForm = true
  }

  private onSavedJoinEvent() {
    this.$apollo.queries.joinEvent.refetch()
  }
}
</script>
