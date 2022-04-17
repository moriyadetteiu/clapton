import { DataTableHeader } from 'vuetify/types/index'
import TableStateInterface from './TableStateInterface'
import TableHeaderDefinition from './TableHeaderDefinitions'

const tableHeaderKeys = [
  'event_date_name',
  'placement_full',
  'circle_placement_classification_name',
  'circle_product_classification_name',
  'circle_product_name',
  'circle_product_price',
  'want_circle_product_quantity',
  'want_priority_name',
  'user_name',
  'circle_memo',
]

export default class TeamCircleListTableState implements TableStateInterface {
  getTableHeaders(): DataTableHeader[] {
    return TableHeaderDefinition.getTableHeaders(tableHeaderKeys)
  }
}
