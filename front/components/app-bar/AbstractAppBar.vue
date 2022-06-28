<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop, Emit } from 'nuxt-property-decorator'
import { User, Event, Team } from '~/apollo/graphql'
import { userStore } from '~/store'

export type UnderwayEventItem = {
  team: Team
  event: Event
}

// @ts-ignore
@Component({})
export default abstract class AbstractAppBar extends Vue {
  @Prop({ type: Array as PropType<UnderwayEventItem[]> })
  protected underwayCircleListItems!: UnderwayEventItem[]

  @Prop({ type: Array as PropType<UnderwayEventItem[]> })
  protected finishedCircleListItems!: UnderwayEventItem[]

  @Emit('logout')
  protected logout(): void {}

  protected get user(): User | null {
    return userStore.loginUser
  }
}
</script>
