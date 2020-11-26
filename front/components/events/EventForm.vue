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
import { EventInput } from '~/apollo/graphql'

@Component
export default class EventForm extends Vue {
  @PropSync('event', { type: Object as PropType<EventInput> } as PropOptions<
    EventInput
  >)
  syncedEvent!: EventInput
}
</script>
