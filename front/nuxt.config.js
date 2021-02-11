import colors from 'vuetify/es5/util/colors'

export default {
  // Global page headers (https://go.nuxtjs.dev/config-head)
  head: {
    titleTemplate: '%s - app',
    title: 'app',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },

  // Global CSS (https://go.nuxtjs.dev/config-css)
  css: [],

  // 環境変数の設定 .envファイルが参照される(https://nuxtjs.org/guide/runtime-config)
  // .envが自動でboolean値として解釈してくれないので、基本的に文字列として扱う・・・
  publicRuntimeConfig: {
    IS_USE_MOCK_SERVER: process.env.IS_USE_MOCK_SERVER === 'true',
    HTTP_ENDPOINT: process.env.HTTP_ENDPOINT ?? 'http://laravel:10800',
    BROWSER_HTTP_ENDPOINT:
      process.env.BROWSER_HTTP_ENDPOINT ?? 'http://localhost:10800',
    MOCK_BROWSER_HTTP_ENDPOINT:
      process.env.MOCK_BROWSER_HTTP_ENDPOINT ?? 'http://localhost:4000',
    MOCK_HTTP_ENDPOINT: process.env.MOCK_HTTP_ENDPOINT ?? 'MOCK_HTTP_ENDPOINT',
    BASE_URL: process.env.BASE_URL ?? 'http://localhost:3000'
  },

  privateRuntimeConfig: {},

  // Plugins to run before rendering page (https://go.nuxtjs.dev/config-plugins)
  plugins: ['~/plugins/vee-validate'],

  router: {
    middleware: 'auth',
  },

  // Auto import components (https://go.nuxtjs.dev/config-components)
  components: true,

  // Modules for dev and build (recommended) (https://go.nuxtjs.dev/config-modules)
  buildModules: [
    // https://go.nuxtjs.dev/typescript
    '@nuxt/typescript-build',
    // https://go.nuxtjs.dev/stylelint
    '@nuxtjs/stylelint-module',
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
  ],

  // Modules (https://go.nuxtjs.dev/config-modules)
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    // https://go.nuxtjs.dev/pwa
    '@nuxtjs/pwa',
    // https://github.com/nuxt-community/apollo-module
    '@nuxtjs/apollo',
    // https://github.com/nuxt-community/community-modules/tree/master/packages/toast
    '@nuxtjs/toast',
  ],

  // Axios module configuration (https://go.nuxtjs.dev/config-axios)
  axios: {},

  // Vuetify module configuration (https://go.nuxtjs.dev/config-vuetify)
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: true,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3,
          edit: colors.green.accent3,
          delete: colors.deepOrange.accent4,
          register: colors.blue.darken2,
        },
      },
    },
  },

  apollo: {
    clientConfigs: {
      default: '~/plugins/apollo/client-config.ts',
    },
    httpLinkOptions: {
      credentials: 'omit',
    },
  },

  // toast module configuration(https://github.com/nuxt-community/community-modules/tree/master/packages/toast)
  toast: {
    position: 'bottom-center',
    duration: 5000,
    register: [
      // Register custom toasts
      {
        name: 'validationError',
        message: '入力項目に誤りがあります',
        options: {
          type: 'error',
        },
      },
    ],
  },

  // Build Configuration (https://go.nuxtjs.dev/config-build)
  build: {
    transpile: ['vee-validate/dist/rules'],
  },

  server: {
    host: '0.0.0.0',
  },
}
