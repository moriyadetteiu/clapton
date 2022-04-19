import { CircleList } from '~/apollo/graphql'
import AbstractIdSelectionFilter from './AbstractIdSelectionFilter'

export default class WantPriorityFilter extends AbstractIdSelectionFilter {
  protected readonly label: string = '優先度'
  protected readonly key: keyof CircleList = 'want_priority_id'
}
