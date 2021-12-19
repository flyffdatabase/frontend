<template>
  <div>
    <nuxt-link to="/">Home</nuxt-link>
    <h2>flyffdb.info</h2>
    <p>Free online database for the soon to be released game Flyff - Project M.</p>

    <input id="search" v-model="q" placeholder="Search..." />

    <ul>
      <li v-for="item in items" :key="item.id">
        <nuxt-link :to="`/items/${item.flyffdb_meta_id}`">{{ item.name.en }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    const q = route.query.q
    let query = $content('items', { deep: true })
      .sortBy('id', 'asc')
    if (q) {
      query = query.search('flyffdb_meta_id', q)
    }
    const items = await query.fetch()
    return {
      q,
      items
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