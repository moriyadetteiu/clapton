<template>
  <validation-observer ref="validationObserver" tag="v-form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedEvent.name"
            label="イベント名"
            rules="required"
            outlined
          />
        </v-col>
        <template v-for="(eventDate, index) in syncedEventDates">
          <v-col cols="5" :key="'name' + index">
            <v-validate-text-field
              :label="index + 1 + '日目'"
              outlined
              v-model="eventDate.name"
              rules="required"
            />
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
          <v-btn color="success" @click="submit">登録</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import { Vue, PropSync, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import { ValidationObserver } from 'vee-validate'
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
  private syncedEvent!: EventInput
  @PropSync('eventDates', {
    type: Array as PropType<EventDateInput[]>,
  } as PropOptions<EventDateInput[]>)
  private syncedEventDates!: EventDateInput[]

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      this.$emit('submit')
    }
  }
}
</script>
