import { DataTableHeader } from 'vuetify/types/index'
import { CircleList } from '~/apollo/graphql'

export default interface TableStateInterface {
  getTableHeaders(): DataTableHeader[]
}
