<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Skills</li>
      </ol>
    </nav>
    
    <ul>
      <li v-for="currentSkill in skills" :key="currentSkill.id">
        <nuxt-link :to="`/skills/${currentSkill.flyffdb_meta_id}`">{{ currentSkill.name.en }}</nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('skills', { deep: true })
      .sortBy('id', 'asc')
    const skills = await query.fetch()
    return {
      skills
    }
  }
}
</script>