<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/achievements">Achievements</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">{{ achievement.name.en }}</li>
      </ol>
    </nav>
    
    <h2>{{ achievement.name.en }}</h2>
    

  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let achievement
    try {
      achievement = await $content('achievements', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'Achievement ' + params.slug + ' not found' })
    }
    return {
      achievement
    }
  },
  head () {
    return {
      title: this.achievement.name.en
    }
  }
}
</script>