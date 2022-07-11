import { Context } from '@nuxt/types'
import { GraphQLError } from 'graphql'
import { onError } from 'apollo-link-error'
import { userStore } from '~/store'

export default (context: Context) => {
  return onError(({ graphQLErrors, networkError }) => {
    if (graphQLErrors) {
      graphQLErrors.forEach(async (graphQLError: GraphQLError) => {
        const category: string = graphQLError?.extensions?.category ?? ''
        const message: string = graphQLError.message ?? ''
        if (
          category === 'authentication' &&
          ~message.indexOf('Unauthenticated')
        ) {
          userStore.logout()
          await context.redirect('/login')
        }
      })
    }
    if (networkError) {
      const message: string = (networkError as any)?.result?.message || ''
      if (message === 'The payload is invalid.') {
        context.redirect('/login')
      }
    }
  })
}
