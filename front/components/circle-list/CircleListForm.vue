<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>
        <temlate v-if="isEditCircle">サークルリスト編集</temlate>
        <template v-else>
          {{ circle.name }}
          <v-spacer />
          <v-btn color="success" @click="editCircle">編集</v-btn>
        </template>
      </v-card-title>
      <v-card-text>
        <circle-form
          v-if="isEditCircle"
          :event-id="eventId"
          :team-id="teamId"
          :join-event-id="joinEventId"
          @saved="onSavedCircle"
        />
        <circle-product-form />
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Prop, PropSync, Component } from 'nuxt-property-decorator'
import CircleForm from './form/CircleForm.vue'
import CircleProductForm from './form/CircleProductForm.vue'
import { Circle } from '~/apollo/graphql'

@Component({
  components: {
    CircleForm,
    CircleProductForm,
  },
})
export default class CircleListForm extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String })
  private joinEventId!: String | null

  @Prop({ type: String })
  private careAboutCircleId!: String | null

  private isEditCircle: boolean = true

  private circle: Circle = {
    id: '',
    name: '',
  }

  private onSavedCircle() {
    // TODO: 登録したサークルを読み込む

    this.isEditCircle = false
  }

  private editCircle() {
    this.isEditCircle = true
  }
}
</script>
