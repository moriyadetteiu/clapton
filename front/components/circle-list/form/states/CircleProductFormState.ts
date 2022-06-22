import { CircleProduct } from '~/apollo/graphql'
import AbstractFormState from './AbstractFormState'

type CircleProductFormStateProps = {
  teamId: String
  circlePlacementId: String
  joinEventId: String
  circleProduct?: CircleProduct | null
}

type CircleProductFormStateEventObservers = {
  saved: () => void
  canceled: () => void
}

export default class CircleProductFormState extends AbstractFormState<
  CircleProductFormStateProps,
  CircleProductFormStateEventObservers
> {
  protected componentName: string = 'circle-product-form'
}
