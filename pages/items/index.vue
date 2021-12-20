<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Items</li>
      </ol>
    </nav>
    
    <div class="card shadow">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Items</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped">
          <tbody>
            <tr v-for="item in items" :key="item.id">
              <td><img :src="`https://gecko-images.flyffdb.info/image/item/${item.icon}`"/></td>
              <td><nuxt-link :to="`/items/${item.flyffdb_meta_id}`">{{ item.name.en }}</nuxt-link></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('items', { deep: true })
      .sortBy('id', 'asc')
    const items = await query.fetch()
    return {
      items
    }
  }
}
</script>