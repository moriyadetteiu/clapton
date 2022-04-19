import { CircleList } from '~/apollo/graphql'
import AbstractIdSelectionFilter from './AbstractIdSelectionFilter'

export default class EventDateFilter extends AbstractIdSelectionFilter {
  protected readonly label: string = '日付'
  protected readonly key: keyof CircleList = 'event_date_id'
}
