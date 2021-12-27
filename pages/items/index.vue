<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Items</li>
      </ol>
    </nav>
    
    <div v-for="category in itemsByCategory" :key="category.name">
      <h2>{{ category.name }}</h2>
      <div class="row">
        <div class="col-md-4" v-for="subcategory in category.subcategories" :key="subcategory.name">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{ subcategory.name }}&nbsp;</h3>
            </div>
            <div class="card-body p-0" style="min-height: 15em; max-height: 15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="item in subcategory.items" :key="item.id">
                    <td>
                      <ItemDisplay :for="item"/>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div> 
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData ({ $http, route }) {
    let itemsByCategory = await $http.$get(`/api/itemsByCategory`);
    itemsByCategory = itemsByCategory.itemsByCategory;
    return {
      itemsByCategory
    }
  }
}
</script>