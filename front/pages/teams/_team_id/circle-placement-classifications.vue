<template>
  <v-row>
    <v-col>
      <circle-placement-classification-form
        :is-open.sync="isOpenFormDialog"
        :circle-placement-classification="selectedCirclePlacementClassification"
        :team-id="teamId"
        @saved="refresh"
      />
      <confirm-dialog v-model="isOpenConfirmDialog" @confirmed="remove" />
      <v-data-table
        :headers="headers"
        :items="circlePlacementClassifications"
        hide-default-footer
        disable-pagination
      >
        <template v-slot:top>
          <v-toolbar>
            <v-toolbar-title>配置分類</v-toolbar-title>
            <v-spacer />
            <v-btn color="register" @click="create"
              ><v-icon>mdi-plus</v-icon>追加</v-btn
            >
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn color="edit" @click="edit(item)"
            ><v-icon left>mdi-pencil</v-icon>編集</v-btn
          >
          <v-btn color="delete" @click="confirmRemove(item)"
            ><v-icon left>mdi-delete</v-icon>削除</v-btn
          >
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import CirclePlacementClassificationForm from '~/components/circle-placement-classification/CirclePlacementClassificationForm.vue'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'
import {
  DeleteCirclePlacementClassificationMutation,
  CirclePlacementClassificationsQuery,
  CirclePlacementClassification,
} from '~/apollo/graphql'

@Component({
  components: { CirclePlacementClassificationForm, ConfirmDialog },
  apollo: {
    circlePlacementClassifications: {
      query: CirclePlacementClassificationsQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { teamId }
      },
    },
  },
})
export default class CirclePlacementClassificationPage extends Vue {
  private circlePlacementClassifications!: CirclePlacementClassification[]
  private headers: DataTableHeader[] = [
    { text: '配置分類名', value: 'name' },
    { text: 'コスト', value: 'cost' },
    { text: '操作', value: 'actions', sortable: false },
  ]

  private isOpenFormDialog: boolean = false
  private isOpenConfirmDialog: boolean = false
  private selectedCirclePlacementClassification?: CirclePlacementClassification

  private defaultSelectedCirclePlacementClassification(): void {
    this.selectedCirclePlacementClassification = {
      id: '',
      name: '',
      cost: 0,
    }
  }

  private create(): void {
    this.defaultSelectedCirclePlacementClassification()
    this.isOpenFormDialog = true
  }

  private edit(
    circlePlacementClassification: CirclePlacementClassification
  ): void {
    this.selectedCirclePlacementClassification = circlePlacementClassification
    this.isOpenFormDialog = true
  }

  private confirmRemove(
    circlePlacementClassification: CirclePlacementClassification
  ): void {
    this.selectedCirclePlacementClassification = circlePlacementClassification
    this.isOpenConfirmDialog = true
  }

  private remove(): void {
    const res = this.$apollo.mutate({
      mutation: DeleteCirclePlacementClassificationMutation,
      variables: {
        id: this.selectedCirclePlacementClassification?.id,
      },
    })

    res
      .then(() => {
        this.$toast.success('削除しました')
        this.isOpenConfirmDialog = false
        this.refresh()
      })
      .catch(() => {
        this.$toast.error('削除に失敗しました')
      })
  }

  private refresh(): void {
    this.$apollo.queries.circlePlacementClassifications.refetch()
  }

  private created(): void {
    this.defaultSelectedCirclePlacementClassification()
  }

  private get teamId(): string {
    return this.$route.params.team_id
  }
}
</script>
