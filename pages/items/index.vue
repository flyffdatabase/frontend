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
  async asyncData ({ $content, route }) {
    let query = $content('items', { deep: true })
      .sortBy('id', 'asc')
    const allItems = await query.fetch();
    const itemsByCategory = {};

    for (const currentItem of allItems) {
      if (!itemsByCategory.hasOwnProperty(currentItem.category)) {
        itemsByCategory[currentItem.category] = {
          'name': currentItem.category,
          'subcategories': {}
        };
      }
      if (!itemsByCategory[currentItem.category].subcategories.hasOwnProperty(currentItem.subcategory)) {
        itemsByCategory[currentItem.category].subcategories[currentItem.subcategory] = {
          'name': currentItem.subcategory,
          'items': []
        };
      }

      itemsByCategory[currentItem.category].subcategories[currentItem.subcategory].items.push(currentItem);
    }

    for (const categoryName of Object.keys(itemsByCategory)) {
      for (const subcategoryName of Object.keys(itemsByCategory[categoryName].subcategories)) {
        if (categoryName == 'armor' || categoryName == 'weapon') [
          itemsByCategory[categoryName].subcategories[subcategoryName].items.sort((a, b) => (a.level > b.level) ? -1 : 1)
        ]
      }
    }
    return {
      itemsByCategory
    }
  }
}
</script>