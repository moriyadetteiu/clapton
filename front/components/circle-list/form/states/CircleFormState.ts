import { Circle, CirclePlacement } from '~/apollo/graphql'
import AbstractFormState from './AbstractFormState'

type CircleFormStateProps = {
  eventId: String
  teamId: String
  joinEventId: String
  circlePlacement?: CirclePlacement | null
  circleId: String | null
}

type CircleFormStateEventObservers = {}

export default class CircleFormState extends AbstractFormState<
  CircleFormStateProps,
  CircleFormStateEventObservers
> {
  protected componentName: string = 'circle-form'
}
