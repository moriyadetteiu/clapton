import { DataTableHeader } from 'vuetify/types/index'

export type ExportColumnCandidate = {
  label: string
  columnName: string
}

export default interface TableStateInterface {
  getTableHeaders(): DataTableHeader[]
  getExportColumnCandidates(): ExportColumnCandidate[]
  getExportGroupingColumnCandidates(): ExportColumnCandidate[]
}
