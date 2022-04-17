import { DataTableHeader } from 'vuetify/types/index'
import InvalidArgumentException from '~/exceptions/InvalidArgumentException'

const headers: DataTableHeader[] = [
  {
    text: '日付',
    value: 'event_date_name',
    width: '75px',
  },
  {
    text: '区分',
    value: 'circle_placement_classification_name',
    width: '105px',
  },
  {
    text: '配置',
    value: 'placement_full',
    width: '90px',
  },
  {
    text: 'サークル名',
    value: 'circle_name',
    width: '250px',
  },
  {
    text: '頒布物分類',
    value: 'circle_product_classification_name',
    width: '110px',
  },
  {
    text: '頒布物名',
    value: 'circle_product_name',
    width: '250px',
  },
  {
    text: '値段',
    value: 'circle_product_price',
    width: '80px',
  },
  {
    text: '個数',
    value: 'want_circle_product_quantity',
    width: '75px',
  },
  {
    text: '優先度',
    value: 'want_priority_name',
    width: '90px',
  },
  {
    text: '購入者名',
    value: 'user_name',
    width: '105px',
  },
  {
    text: '備考',
    value: 'circle_memo',
    width: '300px',
  },
]

export default class TableHeaderDefinition {
  public static getTableHeaders(keys: string[]): DataTableHeader[] {
    return keys.map<DataTableHeader>((key) => {
      const foundHeader = headers.find((header) => header.value === key)

      if (foundHeader === undefined) {
        throw new InvalidArgumentException(`${key} has not defined`)
      }

      return foundHeader
    })
  }
}
