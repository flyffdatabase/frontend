<template>
  <div>
    <nuxt-link to="/">Home</nuxt-link>
    <h2>flyffdb.info</h2>
    <p>Free online database for the soon to be released game Flyff - Project M.</p>

    <input id="search" v-model="q" placeholder="Search..." />

    <ul>
      <li v-for="world in worlds" :key="world.id">
        {{ world.name.en }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    const q = route.query.q
    let query = $content('worlds', { deep: true })
      .sortBy('id', 'asc')
    if (q) {
      query = query.search('flyffdb_meta_id', q)
      // OR query = query.search('title', q)
    }
    const worlds = await query.fetch()
    return {
      q,
      worlds
    }
  },
  watch: {
    q () {
      this.$router.replace({ query: this.q ? { q: this.q } : undefined }).catch(() => { })
    }
  },
  watchQuery: true
}
</script>