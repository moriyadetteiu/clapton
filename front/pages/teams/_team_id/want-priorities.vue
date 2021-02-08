<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import WantPriorityForm from '~/components/want-priority/WantPriorityForm.vue'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'
import {
  DeleteWantPriorityMutation,
  WantPrioritiesQuery,
  WantPriority,
} from '~/apollo/graphql'
import AbstractMasterPage from '~/components/masters/AbstractMasterPage.vue'

@Component({
  components: { WantPriorityForm, ConfirmDialog },
  apollo: {
    models: {
      query: WantPrioritiesQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { teamId }
      },
      update(data) {
        return data.wantPriorities
      },
    },
  },
})
export default class WantPriorityPage extends AbstractMasterPage<WantPriority> {
  protected readonly headers: DataTableHeader[] = [
    { text: '優先度名', value: 'name' },
    { text: '操作', value: 'actions', sortable: false },
  ]

  protected readonly defaultSelectedModel: WantPriority = {
    id: '',
    name: '',
  }

  protected readonly deleteMutation = DeleteWantPriorityMutation

  protected readonly formComponentName: string = 'want-priority-form'

  protected readonly title: string = '優先度'
}
</script>
