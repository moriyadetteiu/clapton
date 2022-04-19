import { CircleList } from '~/apollo/graphql'
import AbstractIdSelectionFilter from './AbstractIdSelectionFilter'

export default class CircleProductClassificationFilter extends AbstractIdSelectionFilter {
  protected readonly label: string = '頒布物分類'
  protected readonly key: keyof CircleList = 'circle_product_classification_id'
}
