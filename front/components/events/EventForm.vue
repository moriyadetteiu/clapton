<template>
  <v-form>
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-text-field
            v-model="syncedEvent.name"
            label="イベント名"
            outlined
          />
        </v-col>
        <template v-for="(eventDate, index) in syncedEventDates">
          <v-col cols="5" :key="'name' + index">
            <v-text-field
              :label="index + 1 + '日目'"
              outlined
              v-model="eventDate.name"
            ></v-text-field>
          </v-col>
          <v-col cols="2" :key="'is_production_day' + index">
            <v-checkbox
              label="イベント当日"
              v-model="eventDate.is_production_day"
            ></v-checkbox>
          </v-col>
          <v-col cols="5" :key="'date' + index">
            <app-date-picker :date.sync="eventDate.date"></app-date-picker>
          </v-col>
        </template>
        <v-col cols="12">
          <slot />
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script lang="ts">
import { Vue, PropSync, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import { EventInput, EventDateInput } from '~/apollo/graphql'
import AppDatePicker from '~/components/common/AppDatePicker.vue'
@Component({
  components: {
    AppDatePicker,
  },
})
export default class EventForm extends Vue {
  @PropSync('event', { type: Object as PropType<EventInput> } as PropOptions<
    EventInput
  >)
  syncedEvent!: EventInput
  @PropSync('eventDates', {
    type: Array as PropType<EventDateInput[]>,
  } as PropOptions<EventDateInput[]>)
  syncedEventDates!: EventDateInput[]
}
</script>
