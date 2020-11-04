import { ApolloServer } from "apollo-server";
import { buildClientSchema } from "graphql";
import { IMocks } from 'graphql-tools';
import scalars from './mocks/scalars';

// https://www.apollographql.com/docs/apollo-server/testing/mocking/#mocking-a-schema-using-introspection
const introspectionResult = require("./generated/schema.json");
const schema = buildClientSchema(introspectionResult);

const mocks = {
  ...scalars
} as IMocks;

const server = new ApolloServer({
  schema,
  mocks,
});

server.listen().then(({ url }) => {
  console.log(`Apollo Server ready at ${url}`);
});
