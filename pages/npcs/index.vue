<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">NPCs</li>
      </ol>
    </nav>
    
    <ul>
      <li v-for="currentNpc in npcs" :key="currentNpc.id">
        <nuxt-link :to="`/npcs/${currentNpc.flyffdb_meta_id}`">{{ currentNpc.name.en }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('npcs', { deep: true })
      .sortBy('id', 'asc')
    const npcs = await query.fetch()
    return {
      npcs
    }
  }
}
</script>