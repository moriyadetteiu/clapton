import { DataTableHeader } from 'vuetify/types/index'
import TableStateInterface, {
  ExportColumnCandidate,
} from './TableStateInterface'
import TableHeaderDefinition from './TableHeaderDefinitions'

export default abstract class AbstractTableState
  implements TableStateInterface
{
  protected abstract tableHeaderKeys: string[]
  protected abstract exportGroupingColumnKeys: string[]

  getTableHeaders(): DataTableHeader[] {
    return TableHeaderDefinition.getTableHeaders(this.tableHeaderKeys)
  }

  public getExportColumnCandidates(): ExportColumnCandidate[] {
    return this.convertTableHeadersToExportColumnCandidates(
      this.getTableHeaders()
    )
  }

  public getExportGroupingColumnCandidates(): ExportColumnCandidate[] {
    const groupingTableHeaderCandidates = this.getTableHeaders().filter(
      (tableHeader) => this.exportGroupingColumnKeys.includes(tableHeader.value)
    )
    return this.convertTableHeadersToExportColumnCandidates(
      groupingTableHeaderCandidates
    )
  }

  private convertTableHeadersToExportColumnCandidates(
    tableHeaders: DataTableHeader[]
  ): ExportColumnCandidate[] {
    return tableHeaders.map((tableHeader) => {
      return {
        label: tableHeader.text,
        columnName: tableHeader.value,
      }
    })
  }
}
