<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Monsters</li>
      </ol>
    </nav>
    
    <ul>
      <li v-for="currentMonster in monsters" :key="currentMonster.id">
        <nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`">{{ currentMonster.name.en }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('monsters', { deep: true })
      .sortBy('id', 'asc')
    const monsters = await query.fetch()
    return {
      monsters
    }
  }
}
</script>