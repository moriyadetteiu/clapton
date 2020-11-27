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
        <template v-for="(date, index) in syncedEventDates">
          <v-col cols="5" :key="index">
            <v-text-field :label="index + 1 + '日目'" outlined></v-text-field>
          </v-col>
          <v-col cols="2" :key="index">
            <v-checkbox label="イベント当日"></v-checkbox>
          </v-col>
          <v-col cols="5" :key="index">
            <v-text-field label="日付" outlined></v-text-field>
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
import { EventInput } from '../../apollo/graphql'

@Component
export default class EventForm extends Vue {
  @PropSync('event', { type: Object as PropType<EventInput> } as PropOptions<
    EventInput
  >)
  syncedEvent!: EventInput
  @PropSync('eventDates', { type: Array })
  syncedEventDates!: Object[]
}
</script>
