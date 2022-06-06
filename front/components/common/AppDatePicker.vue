<template>
  <v-menu
    v-model="isOpenMenu"
    :close-on-content-click="false"
    transition="scale-transition"
    :nudge-right="40"
    offset-y
    min-width="290px"
  >
    <template #activator="{ on }">
      <v-text-field :value="date" v-bind="$attrs" type="date" @input="input">
        <template #append>
          <v-icon v-on="on">mdi-calendar</v-icon>
        </template>
      </v-text-field>
    </template>
    <v-date-picker
      :value="date"
      locale="jp-ja"
      :day-format="(date) => new Date(date).getDate()"
      light
      no-title
      @input="
        isOpenMenu = false
        input($event)
      "
    ></v-date-picker>
  </v-menu>
</template>

<script lang="ts">
import { Vue, Model, Emit, Component } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class AppDatePicker extends Vue {
  @Model('input', { type: String, required: true })
  private date!: String

  private isOpenMenu: Boolean = false

  @Emit()
  private input(inputValue: string) {
    return inputValue
  }
}
</script>

<style scoped>
.v-text-field >>> input::-webkit-calendar-picker-indicator {
  display: none;
}
</style>
