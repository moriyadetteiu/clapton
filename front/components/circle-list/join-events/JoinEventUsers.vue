<template>
  <v-dialog v-model="isOpen">
    <v-card>
      <v-card-title class="headline">参加者リスト</v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="items"
          hide-default-footer
          disable-pagination
        >
          <template v-slot:body.append>
            <tr>
              <td>参加人数</td>
              <td v-for="eventDate in eventDates" :key="eventDate.id">
                {{ countJoinEventDateUsers(eventDate.id) }}人
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types'
import { JoinEvent, EventDate, JoinEventDate } from '~/apollo/graphql'

const joinLabel = '参加'
const notJoinLabel = '不参加'

@Component({})
export default class JoinEventUsers extends Vue {
  @Prop({ type: Boolean, required: true })
  private value!: Boolean

  @Prop({ type: Array as PropType<JoinEvent[]> })
  private joinEvents!: JoinEvent[]

  @Prop({ type: Array as PropType<EventDate[]> })
  private eventDates!: EventDate[]

  private get headers(): DataTableHeader[] {
    const nameHeader: DataTableHeader = {
      text: '名前',
      value: 'userName',
    }
    const isJoinHeaders: DataTableHeader[] = this.eventDates.map(
      (eventDate: EventDate) => {
        const header: DataTableHeader = {
          text: eventDate.name,
          value: eventDate.id,
        }
        return header
      }
    )

    return [nameHeader].concat(isJoinHeaders)
  }

  private get items() {
    return this.joinEvents.flatMap((joinEvent: JoinEvent) => {
      const item: any = {
        userName: joinEvent.user.name,
      }
      joinEvent.joinEventDates.forEach((joinEventDate: JoinEventDate) => {
        item[joinEventDate.event_date_id] = joinEventDate.is_join
          ? joinLabel
          : notJoinLabel
      })
      return item
    })
  }

  private get isOpen(): Boolean {
    return this.value
  }

  private set isOpen(isOpen: Boolean) {
    this.$emit('input', isOpen)
  }

  private countJoinEventDateUsers(eventDateId: string): number {
    return this.items.reduce((prev: number, item: any) => {
      return prev + (item[eventDateId] === joinLabel ? 1 : 0)
    }, 0)
  }
}
</script>
