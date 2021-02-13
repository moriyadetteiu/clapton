import { Context } from '@nuxt/types'
import { ApolloHelpers } from '@nuxtjs/apollo'
import { GraphQLError } from 'graphql'
import { onError } from 'apollo-link-error'

export default (context: Context & { $apolloHelpers: ApolloHelpers }) => {
  return onError(({ graphQLErrors }) => {
    if (graphQLErrors) {
      graphQLErrors.forEach(async (graphQLError: GraphQLError) => {
        const category: string = graphQLError?.extensions?.category ?? ''
        const message: string = graphQLError.message ?? ''
        if (
          category === 'authentication' &&
          ~message.indexOf('Unauthenticated')
        ) {
          if (!process.server) {
            await context.$apolloHelpers.onLogout()
            location.reload()
          }
          await context.redirect('/login')
        }
      })
    }
  })
}
