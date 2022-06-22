import { CircleProduct } from '~/apollo/graphql'
import AbstractFormState from './AbstractFormState'

type WantMeTooFormStateProps = {
  teamId: String
  joinEventId: String
  circleProduct: CircleProduct
}

type WantMeTooFormStateEventObservers = {
  saved: () => void
  canceled: () => void
}

export default class WantMeTooFormState extends AbstractFormState<
  WantMeTooFormStateProps,
  WantMeTooFormStateEventObservers
> {
  protected componentName: string = 'want-me-too-form'
}
