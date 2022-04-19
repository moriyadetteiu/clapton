import { CircleList } from '~/apollo/graphql'
import AbstractIdSelectionFilter from './AbstractIdSelectionFilter'

export default class CirclePlacementClassificationFilter extends AbstractIdSelectionFilter {
  protected readonly label: string = '区分'
  protected readonly key: keyof CircleList =
    'circle_placement_classification_id'
}
