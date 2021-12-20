<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/quests">Quests</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">{{ quest.name.en }}</li>
      </ol>
    </nav>
    
    <h2>{{ quest.name.en }}</h2>
    

  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let quest
    try {
      quest = await $content('quests', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'Quest ' + params.slug + ' not found' })
    }
    return {
      quest
    }
  },
  head () {
    return {
      title: this.quest.name.en
    }
  }
}
</script>