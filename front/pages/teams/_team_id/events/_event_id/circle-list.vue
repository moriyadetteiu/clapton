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
    <join-event-users
      v-model="isOpenJoinEventUsers"
      :join-events="joinEventUsers"
      :event-dates="event.eventDates"
    />
    <v-expansion-panels v-model="joinEventExpansionPanelOpenIndex" accordion>
      <v-expansion-panel>
        <v-expansion-panel-header>参加情報 </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-btn color="register" @click="openJoinEventForm">登録</v-btn>
          <v-btn color="primary" @click="openJoinEventUsers">一覧</v-btn>
          <v-row v-for="eventDate in event.eventDates" :key="eventDate.id">
            <v-col cols="4" class="font-weight-bold">
              {{ eventDate.name }}
            </v-col>
            <v-col cols="8">{{ isJoin(eventDate) ? '参加' : '不参加' }}</v-col>
          </v-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>

    <template v-if="joinEvent">
      <circle-list-form
        :is-open.sync="isOpenCircleListForm"
        :event-id="$route.params.event_id"
        :team-id="$route.params.team_id"
        :join-event-id="joinEvent.id"
        :editing-circle-id.sync="editingCircleId"
        @saved="onSavedCircle"
      />

      <v-tabs v-model="selectedCircleListTabIndex" class="mt-5">
        <v-tab v-for="circleListTab in circleListTabs" :key="circleListTab.key">
          {{ circleListTab.label }}
        </v-tab>
      </v-tabs>
      <circle-list-table
        v-if="circleListState"
        :circle-lists="circleLists"
        :table-state="circleListState"
        :filter-condition-items="circleListTableFilterConditionItems"
        @open-circle-list-form="openCircleListForm"
      />
      <favorite-circle-list-table
        v-if="isSelectedFavoriteListTab"
        :favorites-with-state="myFavoritesInEvent"
        :event-id="$route.params.event_id"
        @open-circle-list-form="openCircleListForm"
      />
    </template>
    <v-card v-else-if="!$apollo.queries.joinEvent.loading" class="mt-4">
      <v-card-text>
        参加情報を登録すると、サークルリストが表示されます。
      </v-card-text>
    </v-card>
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
  JoinEventUsersQuery,
  EventWithDateQuery,
  FindJoinEventWithDateQuery,
  FavoriteWithState,
  MyFavoritesInEventsQuery,
  User,
  WantPrioritiesQuery,
  CirclePlacementClassificationsQuery,
  WantPriority,
  CirclePlacementClassification,
  CircleProductClassification,
  CircleProductClassificationsQuery,
} from '~/apollo/graphql'
import JoinEventForm from '~/components/join-event/JoinEventForm.vue'
import JoinEventUsers from '~/components/circle-list/join-events/JoinEventUsers.vue'
import CircleListForm from '~/components/circle-list/CircleListForm.vue'
import CircleListTable from '~/components/circle-list/CircleListTable.vue'
import FavoriteCircleListTable from '~/components/circle-list/FavoriteCircleListTable.vue'
import { FilterConditionItems } from '~/components/circle-list/table/filters/filterInterfaces'
import TableStateInterface from '~/components/circle-list/table/TableStateInterface'
import MyCircleListTableState from '~/components/circle-list/table/MyCircleListTableState'
import TeamCircleListTableState from '~/components/circle-list/table/TeamCircleListTableState'
import { userStore, favoriteStore } from '~/store'

type CircleListTab = {
  key: string
  label: string
}

@Component({
  head() {
    return {
      title: 'サークルリスト',
    }
  },
  components: {
    JoinEventForm,
    CircleListForm,
    CircleListTable,
    JoinEventUsers,
    FavoriteCircleListTable,
  },
  apollo: {
    event: {
      query: EventWithDateQuery,
      variables() {
        const eventId: string = this.$route.params.event_id
        return { id: eventId }
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
      result(data) {
        this.joinEventExpansionPanelOpenIndex = data.data?.findJoinEvent
          ? null
          : 0
      },
    },
    joinEventUsers: {
      query: JoinEventUsersQuery,
      update(data): JoinEvent[] {
        return data.joinEvents
      },
      variables() {
        const teamId: string = this.$route.params.team_id
        const eventId: string = this.$route.params.event_id

        return {
          teamId,
          eventId,
        }
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
        return (this.joinEvent?.id || null) === null
      },
    },
    teamCircleLists: {
      query: TeamCircleListsQuery,
      variables() {
        const teamId = this.$route.params.team_id
        const eventId = this.$route.params.event_id
        return { teamId, eventId }
      },
      update(data): CircleList[] {
        return data.teamCircleLists
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
    myFavoritesInEvent: {
      query: MyFavoritesInEventsQuery,
      variables() {
        const eventId = this.$route.params.event_id
        return { eventId }
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

  private joinEvent: JoinEvent | null = null

  private myCircleLists: CircleList[] = []

  private teamCircleLists: CircleList[] = []

  private wantPriorities: WantPriority[] = []

  private circlePlacementClassifications: CirclePlacementClassification[] = []

  private circleProductClassifications: CircleProductClassification[] = []

  private joinEventUsers: JoinEvent[] = []

  private myFavoritesInEvent: FavoriteWithState[] = []

  private joinEventExpansionPanelOpenIndex: number | null = null

  private isOpenCircleListForm: boolean = false

  private isOpenJoinEventUsers: boolean = false

  private editingCircleId: String | null = null

  private selectedCircleListTabIndex: number = 1

  private readonly circleListTabs: CircleListTab[] = [
    {
      key: 'favoriteList',
      label: 'お気に入り',
    },
    {
      key: 'myList',
      label: 'マイリスト',
    },
    {
      key: 'teamList',
      label: 'チームリスト',
    },
  ]

  private get user(): User {
    return userStore.loginUserOrEmptyUser
  }

  private get circleListState(): TableStateInterface | null {
    const circleListStateMap: { [key: string]: TableStateInterface } = {
      myList: new MyCircleListTableState(),
      teamList: new TeamCircleListTableState(),
    }

    return circleListStateMap[this.selectedCircleListTab.key] ?? null
  }

  private get selectedCircleListTab(): CircleListTab {
    return this.circleListTabs[this.selectedCircleListTabIndex]
  }

  private get circleLists(): CircleList[] {
    const circleListMap: { [key: string]: CircleList[] } = {
      myList: this.myCircleLists,
      teamList: this.teamCircleLists,
    }

    return circleListMap[this.selectedCircleListTab.key] ?? []
  }

  private get circleListTableFilterConditionItems(): FilterConditionItems {
    return {
      eventDates: this.event.eventDates as EventDate[],
      circlePlacementClassifications: this.circlePlacementClassifications,
      wantPriorities: this.wantPriorities,
      circleProductClassifications: this.circleProductClassifications,
    }
  }

  private get isSelectedFavoriteListTab(): boolean {
    return this.selectedCircleListTab.key === 'favoriteList'
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
    this.$apollo.queries.joinEventUsers.refetch()
  }

  private openCircleListForm(circleList: { circle_id: string } | null): void {
    this.editingCircleId = circleList?.circle_id ?? null // eslint-disable-line camelcase
    this.isOpenCircleListForm = true
  }

  private onSavedCircle(): void {
    this.$apollo.queries.myCircleLists.refetch()
    this.$apollo.queries.teamCircleLists.refetch()
    this.$apollo.queries.myFavoritesInEvent.refetch()
  }

  private openJoinEventUsers(): void {
    this.isOpenJoinEventUsers = true
  }

  public created(): void {
    favoriteStore.fetchMyFavorites()
  }
}
</script>
