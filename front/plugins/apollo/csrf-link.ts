import { ApolloLink } from 'apollo-link'

const findCsrfTokenInCookie = (): string | null => {
  const [_, token] = document.cookie
    .split(';')
    .map((value: string) => {
      return value.split(/(?<=^[^=]+?)=/)
    })
    .find(([key, _]) => {
      return key === 'XSRF-TOKEN'
    }) ?? [null, null]

  return token && decodeURIComponent(token)
}

export const csrfLink = new ApolloLink((operation, forward) => {
  const csrfToken: string | null = findCsrfTokenInCookie()

  operation.setContext({
    headers: {
      'X-XSRF-TOKEN': csrfToken,
    },
  })
  return forward(operation)
})
