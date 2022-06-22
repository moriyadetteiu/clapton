import { CircleProduct } from '~/apollo/graphql'
import AbstractFormState from './AbstractFormState'

type CircleProductStateProps = {
  circleProducts: CircleProduct[]
}

type CircleProductStateEventObservers = {
  'delete-circle-product': () => void
  'edit-circle-product': (circleProduct: CircleProduct) => void
  'want-me-too': (circleProduct: CircleProduct) => void
  'add-circle-product': () => void
}

export default class CircleProductsState extends AbstractFormState<
  CircleProductStateProps,
  CircleProductStateEventObservers
> {
  protected componentName: string = 'circle-products'
}
