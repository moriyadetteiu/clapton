<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>Excelダウンロード</v-card-title>
      <v-card-text>
        <v-expansion-panels>
          <v-expansion-panel>
            <v-expansion-panel-header>詳細設定</v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-row>
                <v-col cols="4">出力項目</v-col>
                <v-col cols="8">
                  <v-chip-group
                    v-model="selectedExportColumnIndexes"
                    active-class="primary"
                    multiple
                    column
                  >
                    <v-chip
                      v-for="exportColumnCandidate in exportColumnCandidates"
                      :key="exportColumnCandidate.name"
                    >
                      {{ exportColumnCandidate.label }}
                    </v-chip>
                  </v-chip-group>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">シートを分ける基準</v-col>
                <v-col cols="8">
                  <v-chip-group
                    v-model="selectedGroupingColumnIndexes"
                    active-class="primary"
                    multiple
                    column
                  >
                    <v-chip
                      v-for="groupingColumnCandidate in groupingColumnCandidates"
                      :key="groupingColumnCandidate.name"
                    >
                      {{ groupingColumnCandidate.label }}
                    </v-chip>
                  </v-chip-group>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="download">ダウンロード</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Prop, PropSync, Component, Watch } from 'nuxt-property-decorator'
import TableStateInterface, {
  ExportColumnCandidate,
} from './TableStateInterface'
import { MakeCircleListsExcelMutation } from '~/apollo/graphql'
import FileDownloader from '~/utils/FileDownloader'

@Component({})
export default class ExportCircleList extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Prop({ type: Object as PropType<TableStateInterface>, required: true })
  private readonly tableState!: TableStateInterface

  @Prop({ type: Array as PropType<string[]>, required: true })
  private readonly circleListIds!: string[]

  private selectedExportColumnIndexes: number[] = []

  private selectedGroupingColumnIndexes: number[] = []

  private get exportColumnCandidates(): ExportColumnCandidate[] {
    return this.tableState.getExportColumnCandidates()
  }

  private get selectedExportColumnNames(): string[] {
    return this.selectedExportColumnIndexes.map(
      (index) => this.exportColumnCandidates[index].columnName
    )
  }

  private get selectedGroupingColumnNames(): string[] {
    return this.selectedGroupingColumnIndexes.map(
      (index) => this.exportColumnCandidates[index].columnName
    )
  }

  private get groupingColumnCandidates(): ExportColumnCandidate[] {
    return this.tableState.getExportGroupingColumnCandidates()
  }

  @Watch('exportColumnCandidates', { immediate: true })
  private onUpdateExportColumnCandidates(): void {
    this.selectedExportColumnIndexes = this.exportColumnCandidates.map(
      (_, index) => index
    )
  }

  private async download() {
    const variables = {
      circleListIds: this.circleListIds,
      groupingColumns: this.selectedGroupingColumnNames,
      exportColumns: this.selectedExportColumnNames,
    }

    const filename = await this.$apollo
      .mutate({
        mutation: MakeCircleListsExcelMutation,
        variables,
      })
      .then((res) => res.data.makeCircleListsExcel.file_name)

    const downloader = new FileDownloader()
    downloader.download(filename)
  }
}
</script>
