module.exports = {
    apps: [
      {
        name: 'flyffdb.info',
        exec_mode: 'cluster',
        instances: 'max', // Or a number of instances
        script: './node_modules/nuxt/bin/nuxt.js',
        args: 'start',
        env: {
          API_URL: "https://flyffdb.info"
        },
      }
    ]
  }