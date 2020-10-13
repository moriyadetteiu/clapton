import { ApolloServer, gql } from "apollo-server";

const typeDefs = gql`
  type Book {
    title: String
  }
  type Query {
    books: [Book]
  }
`;

const books = [
  {
    title: "Harry Potter and the Chamber of Secrets",
  },
  {
    title: "Jurassic Park",
  }
];

const resolvers = {
  Query: {
    books: () => books
  },
};

const server = new ApolloServer({
  typeDefs,
  resolvers,
});

server.listen().then(({ url }) => {
  console.log(`Apollo Server ready at ${url}`);
});
