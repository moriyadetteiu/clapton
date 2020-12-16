<template>
  <v-menu
    v-model="isOpenMenu"
    :close-on-content-click="false"
    transition="scale-transition"
    :nudge-right="40"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        v-model="syncedDate"
        :label="label"
        outlined
        type="date"
        :error-messages="errors"
      >
        <template v-slot:append>
          <v-icon v-on="on">mdi-calendar</v-icon>
        </template>
      </v-text-field>
    </template>
    <v-date-picker
      v-model="syncedDate"
      locale="jp-ja"
      :day-format="(date) => new Date(date).getDate()"
      light
      no-title
      @input="menu = false"
    ></v-date-picker>
  </v-menu>
</template>

<script lang="ts">
import { Vue, PropSync, Prop, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'

@Component
export default class AppDatePicker extends Vue {
  @PropSync('date', { type: String as PropType<string> } as PropOptions<string>)
  syncedDate!: string

  @Prop({ type: String })
  private label?: String

  private isOpenMenu: Boolean = false
}
</script>

<style scoped>
.v-text-field >>> input::-webkit-calendar-picker-indicator {
  display: none;
}
</style>
