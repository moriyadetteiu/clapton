<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import CirclePlacementClassificationForm from '~/components/circle-placement-classification/CirclePlacementClassificationForm.vue'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'
import {
  DeleteCirclePlacementClassificationMutation,
  CirclePlacementClassificationsQuery,
  CirclePlacementClassification,
} from '~/apollo/graphql'
import AbstractMasterPage from '~/components/masters/AbstractMasterPage.vue'

@Component({
  components: { CirclePlacementClassificationForm, ConfirmDialog },
  apollo: {
    models: {
      query: CirclePlacementClassificationsQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { teamId }
      },
      update(data) {
        return data.circlePlacementClassifications
      },
    },
  },
})
export default class CirclePlacementClassificationPage extends AbstractMasterPage<
  CirclePlacementClassification
> {
  protected readonly headers: DataTableHeader[] = [
    { text: '配置分類名', value: 'name' },
    { text: 'コスト', value: 'cost' },
    { text: '操作', value: 'actions', sortable: false },
  ]

  protected readonly defaultSelectedModel: CirclePlacementClassification = {
    id: '',
    name: '',
    cost: 0,
  }

  protected readonly deleteMutation = DeleteCirclePlacementClassificationMutation

  protected readonly formComponentName: string =
    'circle-placement-classification-form'

  protected readonly title: string = '配置分類'
}
</script>
