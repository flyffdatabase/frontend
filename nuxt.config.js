export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'flyffdb',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', type: 'text/css', href: 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback' },
      { rel: 'stylesheet', type: 'text/css', href: '/plugins/fontawesome-free/css/all.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/plugins/overlayScrollbars/css/OverlayScrollbars.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/dist/css/adminlte.min.css' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
  ],

  env: {
    baseUrl: 'https://flyffdb.info'
  },

  target: 'server',

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: '~/plugins/penya_format.js' },
    { src: '~/plugins/thousands_format.js' },
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  serverMiddleware: [
    { path: "/api/", handler: "~/server-middleware/api.js" },
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxt/content',
    '@nuxt/http'
  ],

  http: {
    // HTTP options here
    baseUrl: 'https://flyffdb.info'
  },

  content: {
    // Only search in title and description
    fullTextSearchFields: ['flyffdb_meta_id']
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  }
}
