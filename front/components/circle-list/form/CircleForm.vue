<template>
  <circle-register
    v-if="!circlePlacement"
    :event-id="eventId"
    :join-event-id="joinEventId"
    :team-id="teamId"
    :circle-id="circleId"
    v-on="$listeners"
  />
  <circle-editor
    v-else
    :event-id="eventId"
    :join-event-id="joinEventId"
    :team-id="teamId"
    :circle-placement="circlePlacement"
    :care-about-circle="careAboutCircle"
    v-on="$listeners"
  />
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import CircleRegister from './circle-form/CircleRegister.vue'
import CircleEditor from './circle-form/CircleEditor.vue'
import { CareAboutCircle, CirclePlacement } from '~/apollo/graphql'

@Component({
  components: { CircleRegister, CircleEditor },
})
export default class CircleForm extends Vue {
  @Prop({ type: String, required: true })
  private eventId!: String

  @Prop({ type: String, required: true })
  private teamId!: String

  @Prop({ type: String, required: true })
  private joinEventId!: string

  @Prop({ type: Object as PropType<CirclePlacement> })
  private circlePlacement!: CirclePlacement | null

  @Prop({ type: Object as PropType<CareAboutCircle>, default: null })
  private careAboutCircle!: CareAboutCircle | null

  @Prop({ type: String })
  private circleId!: string | null
}
</script>
