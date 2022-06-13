import AbstractTableState from './AbstractTableState'

export default class TeamCircleListTableState extends AbstractTableState {
  protected tableHeaderKeys: string[] = [
    'event_date_name',
    'placement_full',
    'circle_name',
    'circle_placement_classification_name',
    'circle_product_classification_name',
    'circle_product_name',
    'circle_product_price',
    'want_circle_product_quantity',
    'want_priority_name',
    'user_name',
    'circle_memo',
  ]

  protected exportGroupingColumnKeys: string[] = [
    'event_date_name',
    'user_name',
  ]
}
