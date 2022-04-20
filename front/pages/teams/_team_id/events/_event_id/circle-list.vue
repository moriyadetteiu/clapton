<template>
  <v-container fluid>
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

    <circle-list-form
      :is-open.sync="isOpenCircleListForm"
      :event-id="$route.params.event_id"
      :team-id="$route.params.team_id"
      :join-event-id="joinEvent ? joinEvent.id : null"
      @saved="onSavedCircle"
    />

    <v-tabs v-model="selectedCircleListTabIndex" class="mt-5">
      <v-tab v-for="circleListTab in circleListTabs" :key="circleListTab.key">
        {{ circleListTab.label }}
      </v-tab>
    </v-tabs>
    <circle-list-table
      :circle-lists="circleLists"
      :table-state="circleListState"
      :filter-condition-items="circleListTableFilterConditionItems"
      @open-circle-list-form="openCircleListForm"
    />
  </v-container>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import {
  CircleList,
  TeamCircleListsQuery,
  Event,
  EventDate,
  JoinEvent,
  JoinEventCircleListsQuery,
  User,
  EventWithDateQuery,
  FindJoinEventWithDateQuery,
  MeQuery,
  WantPrioritiesQuery,
  CirclePlacementClassificationsQuery,
  WantPriority,
  CirclePlacementClassification,
  CircleProductClassification,
  CircleProductClassificationsQuery,
} from '~/apollo/graphql'
import JoinEventForm from '~/components/join-event/JoinEventForm.vue'
import CircleListForm from '~/components/circle-list/CircleListForm.vue'
import CircleListTable from '~/components/circle-list/CircleListTable.vue'
import { FilterConditionItems } from '~/components/circle-list/table/filters/filterInterfaces'
import TableStateInterface from '~/components/circle-list/table/TableStateInterface'
import MyCircleListTableState from '~/components/circle-list/table/MyCircleListTableState'
import TeamCircleListTableState from '~/components/circle-list/table/TeamCircleListTableState'

type CircleListTab = {
  key: string
  label: string
}

@Component({
  components: {
    JoinEventForm,
    CircleListForm,
    CircleListTable,
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

        // TODO: gqlの変数をキャメルケースに統一
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
    myCircleLists: {
      query: JoinEventCircleListsQuery,
      variables() {
        const joinEventId = this.joinEvent.id
        return { joinEventId }
      },
      update(data): CircleList[] {
        return data.joinEventCircleLists
      },
      skip(): boolean {
        return (
          this.selectedCircleListTab.key !== 'myList' ||
          (this.joinEvent?.id || null) === null
        )
      },
    },
    teamCircleLists: {
      query: TeamCircleListsQuery,
      variables() {
        const teamId = this.$route.params.team_id
        return { teamId }
      },
      update(data): CircleList[] {
        return data.teamCircleLists
      },
      skip(): boolean {
        return this.selectedCircleListTab.key !== 'teamList'
      },
    },
    wantPriorities: {
      query: WantPrioritiesQuery,
      variables() {
        const teamId = this.$route.params.team_id
        return { teamId }
      },
      update(data): WantPriority[] {
        return data.wantPriorities
      },
    },
    circlePlacementClassifications: {
      query: CirclePlacementClassificationsQuery,
      variables() {
        const teamId = this.$route.params.team_id
        return { teamId }
      },
      update(data): CirclePlacementClassification[] {
        return data.circlePlacementClassifications
      },
    },
    circleProductClassifications: {
      query: CircleProductClassificationsQuery,
      variables() {
        const teamId = this.$route.params.team_id
        return { teamId }
      },
      update(data): CircleProductClassification[] {
        return data.circleProductClassifications
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

  private myCircleLists: CircleList[] = []

  private teamCircleLists: CircleList[] = []

  private wantPriorities: WantPriority[] = []

  private circlePlacementClassifications: CirclePlacementClassification[] = []

  private circleProductClassifications: CircleProductClassification[] = []

  private isOpenCircleListForm: boolean = false

  private selectedCircleListTabIndex: number = 0

  private readonly circleListTabs: CircleListTab[] = [
    {
      key: 'myList',
      label: 'マイリスト',
    },
    {
      key: 'teamList',
      label: '全体リスト',
    },
  ]

  private get circleListState(): TableStateInterface {
    const circleListStateMap: { [key: string]: TableStateInterface } = {
      myList: new MyCircleListTableState(),
      teamList: new TeamCircleListTableState(),
    }

    return circleListStateMap[this.selectedCircleListTab.key]
  }

  private get selectedCircleListTab(): CircleListTab {
    return this.circleListTabs[this.selectedCircleListTabIndex]
  }

  private get circleLists(): CircleList[] {
    const circleListMap: { [key: string]: CircleList[] } = {
      myList: this.myCircleLists,
      teamList: this.teamCircleLists,
    }

    return circleListMap[this.selectedCircleListTab.key]
  }

  private get circleListTableFilterConditionItems(): FilterConditionItems {
    return {
      eventDates: this.event.eventDates as EventDate[],
      circlePlacementClassifications: this.circlePlacementClassifications,
      wantPriorities: this.wantPriorities,
      circleProductClassifications: this.circleProductClassifications,
    }
  }

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

  private openCircleListForm(): void {
    this.isOpenCircleListForm = true
  }

  private onSavedCircle(): void {
    this.$apollo.queries.myCircleLists.refetch()
    this.$apollo.queries.teamCircleLists.refetch()
  }
}
</script>
