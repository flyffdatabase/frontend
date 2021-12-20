<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Achievements</li>
      </ol>
    </nav>
    
    <ul>
      <li v-for="currentAchievement in achievements" :key="currentAchievement.id">
        <nuxt-link :to="`/achievements/${currentAchievement.flyffdb_meta_id}`">{{ currentAchievement.name.en }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('achievements', { deep: true })
      .sortBy('id', 'asc')
    const achievements = await query.fetch()
    return {
      achievements
    }
  }
}
</script>