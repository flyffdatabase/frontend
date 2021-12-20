<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/npcs">NPCs</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">{{ item.name.en }}</li>
      </ol>
    </nav>
    
    <h2>{{ item.name.en }}</h2>
    

  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let item
    try {
      item = await $content('npcs', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'NPC ' + params.slug + ' not found' })
    }
    return {
      item
    }
  },
  head () {
    return {
      title: this.item.name.en
    }
  }
}
</script>