import { ApolloError } from 'apollo-client/errors/ApolloError.d'

export interface ValidationItem {
  rules?: string
  attribute?: string
}

interface ValidationItems {
  [name: string]: ValidationItem
}

interface backendErrors {
  [name: string]: string[]
}

export default class Validation {
  protected items: ValidationItems
  protected backendErrors: backendErrors = {}

  constructor(items: ValidationItems) {
    this.items = items
  }

  public getItem(name: string): ValidationItem {
    return this.items[name]
  }

  public getItems(): ValidationItems {
    return this.items
  }

  public setBackendErrorsFromAppolo(apolloError: ApolloError): void {
    let errors = {}
    apolloError.graphQLErrors
      .filter(
        (graphQLError) =>
          graphQLError.extensions?.category === 'validationError'
      )
      .flatMap((error) => error.extensions?.errors as Object)
      .forEach((error) => {
        errors = {
          ...errors,
          ...error,
        }
      })

    this.backendErrors = errors
  }

  public getErrorMessages(name: string): string[] {
    return this.backendErrors[name] ?? []
  }
}
