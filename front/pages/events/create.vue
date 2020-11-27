<template>
  <event-form :event="event" :eventDates="eventDates">
    <v-btn color="primary" @click="addDate">日程追加</v-btn>
    <v-btn color="success" @click="submit">登録</v-btn>
  </event-form>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { EventInput, CreateEventMutation } from '~/apollo/graphql'
import EventForm from '~/components/events/EventForm.vue'

@Component({
  components: {
    EventForm,
  },
})
export default class CreateEvent extends Vue {
  event: EventInput = {
    name: '',
  }

  eventDates:Object[] = []

  addDate(): void {
    this.eventDates.push({})
  }

  submit(): void {
    const res = this.$apollo.mutate({
      mutation: CreateEventMutation,
      variables: {
        input: this.event,
      },
    })
    res
      .then(() => {
        // TODO: リダイレクト処理をかける
      })
      .catch(() => {
        // TODO: バリデーション失敗時にはエラーが出るようにする
      })
  }
}
</script>
