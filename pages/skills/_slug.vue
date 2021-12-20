<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/skills">Skills</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">{{ skill.name.en }}</li>
      </ol>
    </nav>
    
    <h2>{{ skill.name.en }}</h2>
    

  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let skill
    try {
      skill = await $content('skills', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'Skill ' + params.slug + ' not found' })
    }
    return {
      skill
    }
  },
  head () {
    return {
      title: this.skill.name.en
    }
  }
}
</script>