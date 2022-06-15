<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import CircleProductClassificationForm from '~/components/circle-product-classification/CircleProductClassificationForm.vue'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'
import {
  DeleteCircleProductClassificationMutation,
  CircleProductClassificationsQuery,
  CircleProductClassification,
} from '~/apollo/graphql'
import AbstractMasterPage from '~/components/masters/AbstractMasterPage.vue'

@Component({
  components: { CircleProductClassificationForm, ConfirmDialog },
  apollo: {
    models: {
      query: CircleProductClassificationsQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { teamId }
      },
      update(data) {
        return data.circleProductClassifications
      },
    },
  },
})
export default class CircleProductClassificationPage extends AbstractMasterPage<CircleProductClassification> {
  protected readonly headers: DataTableHeader[] = [
    { text: '頒布物分類名', value: 'name' },
    { text: '操作', value: 'actions', sortable: false },
  ]

  protected readonly defaultSelectedModel: CircleProductClassification = {
    id: '',
    name: '',
  }

  protected readonly deleteMutation = DeleteCircleProductClassificationMutation

  protected readonly formComponentName: string =
    'circle-product-classification-form'

  protected readonly title: string = '頒布物分類'
}
</script>
