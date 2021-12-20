<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/monsters">Monsters</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">{{ monster.name.en }}</li>
      </ol>
    </nav>
    
    <h2>{{ monster.name.en }}</h2>
    

  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let monster
    try {
      monster = await $content('monsters', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'Monster ' + params.slug + ' not found' })
    }
    return {
      monster
    }
  },
  head () {
    return {
      title: this.monster.name.en
    }
  }
}
</script>