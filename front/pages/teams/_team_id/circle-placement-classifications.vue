<template>
  <v-row>
    <v-col>
      <circle-placement-classification-form
        :is-open.sync="isOpenDialog"
        :circle-placement-classification="selectedCirclePlacementClassification"
        :team-id="teamId"
        @saved="refresh"
      />
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
          <v-btn color="delete"><v-icon left>mdi-delete</v-icon>削除</v-btn>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import CirclePlacementClassificationForm from '~/components/circle-placement-classification/CirclePlacementClassificationForm.vue'
import {
  CirclePlacementClassificationsQuery,
  CirclePlacementClassification,
} from '~/apollo/graphql'

@Component({
  components: { CirclePlacementClassificationForm },
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

  private isOpenDialog = false
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
    this.isOpenDialog = true
  }

  private edit(
    circlePlacementClassification: CirclePlacementClassification
  ): void {
    this.selectedCirclePlacementClassification = circlePlacementClassification
    this.isOpenDialog = true
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
