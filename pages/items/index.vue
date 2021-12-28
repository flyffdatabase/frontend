<template>
  <div class="pt-3">
    <div v-for="category in itemsByCategory" :key="category.name">
      <div class="row">
        <div class="col-xl-3 col-md-4 mb-1">
          <div class="card shadow">
            <div class="card-header">
              <h2 class="card-title">{{ category.name | capitalize }}</h2>
            </div>
          </div>
        </div>
        <div class="col-xl-9 col-md-8 mb-8">
          <div class="row">
            <div class="col-xl-6 col-sm-12" v-for="subcategory in category.subcategories" :key="subcategory.name">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{ category.name | capitalize }} / {{ subcategory.name | capitalize }}&nbsp;</h3>
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