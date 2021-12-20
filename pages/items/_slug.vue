<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/items">Items</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">{{ item.name.en }}</li>
      </ol>
    </nav>
    
    <h2>{{ item.name.en }}</h2>
    <table>
      <thead>
        <tr><th colspan="2">Data</th></tr>
      </thead>
      <tbody>
        <template v-if="item.category == 'weapon'">
          <tr><td>Atk</td><td>{{ item.minAttack }} - {{ item.maxAttack}}</td></tr>
        </template>
        <tr><td>Buy Price: </td><td>{{ item.sellPrice | penya }}</td></tr>
        <tr><td>Sell Price: </td><td>{{ item.sellPrice / 4 | penya }}</td></tr>
      </tbody> 
    </table>
    

    <template v-if="item.abilities">
        <h1>Abilities</h1>
        <ul>
            <li v-for="itemability in item.abilities">
                {{ itemability.parameter }}
            </li>
        </ul>
    </template>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let item
    try {
      item = await $content('items', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'Item ' + params.slug + ' not found' })
    }
    return {
      item
    }
  },
  head () {
    return {
      title: this.item.name.en,
      description: this.item.raw_description.en
    }
  }
}
</script>