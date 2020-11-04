import { Context } from '@nuxt/types';

export default (context: Context) => {
  const IS_USE_MOCK_SERVER: boolean = context.$config.IS_USE_MOCK_SERVER;
  const MOCK_HTTP_ENDPOINT: string = context.$config.MOCK_HTTP_ENDPOINT;
  const MOCK_BROWSER_HTTP_ENDPOINT: string = context.$config.MOCK_BROWSER_HTTP_ENDPOINT;
  const HTTP_ENDPOINT: string = context.$config.HTTP_ENDPOINT;
  const BROWSER_HTTP_ENDPOINT: string = context.$config.BROWSER_HTTP_ENDPOINT;

  return {
    httpEndpoint: IS_USE_MOCK_SERVER ? MOCK_HTTP_ENDPOINT : HTTP_ENDPOINT,
    browserHttpEndpoint: IS_USE_MOCK_SERVER ? MOCK_BROWSER_HTTP_ENDPOINT : BROWSER_HTTP_ENDPOINT,
  }
};
