<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Quests</li>
      </ol>
    </nav>
    
    <ul>
      <li v-for="currentQuest in quests" :key="currentQuest.id">
        <nuxt-link :to="`/quests/${currentQuest.flyffdb_meta_id}`">{{ currentQuest.name.en }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('quests', { deep: true })
      .sortBy('minLevel', 'asc')
    const quests = await query.fetch()
    return {
      quests
    }
  }
}
</script>