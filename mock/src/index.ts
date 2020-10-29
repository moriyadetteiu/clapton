import { ApolloServer, gql } from "apollo-server";
import { buildClientSchema  } from "graphql";

const introspectionResult = require("./generated/schema.json");

const schema = buildClientSchema(introspectionResult);

const server = new ApolloServer({
  schema,
  mocks: true,
});

server.listen().then(({ url }) => {
  console.log(`Apollo Server ready at ${url}`);
});
