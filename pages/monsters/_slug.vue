<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/monsters">Monsters</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
      </ol>
    </nav>
    
    <h2>{{ monster.name.en }}</h2>
    
    <table>
      <thead>
        <tr><th colspan="2">Data</th></tr>
      </thead>
      <tbody>
        <tr><td>Level: </td><td>{{ monster.level }}</td></tr>
        <tr><td>HP: </td><td>{{ monster.hp | thousands }}</td></tr>
        <tr><td>Defense: </td><td>{{ monster.defense | thousands }}</td></tr>
      </tbody> 
    </table>
    
    <h3>Drop Information</h3>
    <div class="row">
      <template v-if="dropItems.unique.length">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Unique item drops</h6>
            </div>
            <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="currentDropItem in dropItems.unique" :key="currentDropItem.id">
                    <td><img :src="`https://gecko-images.flyffdb.info/image/item/${currentDropItem.icon}`"/></td>
                    <td><nuxt-link :to="`/items/${currentDropItem.flyffdb_meta_id}`">{{ currentDropItem.name.en }}</nuxt-link></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </template>
      
      <template v-if="dropItems.veryrare.length">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Very Rare item drops</h6>
            </div>
            <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="currentDropItem in dropItems.veryrare" :key="currentDropItem.id">
                    <td><img :src="`https://gecko-images.flyffdb.info/image/item/${currentDropItem.icon}`"/></td>
                    <td><nuxt-link :to="`/items/${currentDropItem.flyffdb_meta_id}`">{{ currentDropItem.name.en }}</nuxt-link></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </template>
      
      <template v-if="dropItems.rare.length">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Rare item drops</h6>
            </div>
            <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="currentDropItem in dropItems.rare" :key="currentDropItem.id">
                    <td><img :src="`https://gecko-images.flyffdb.info/image/item/${currentDropItem.icon}`"/></td>
                    <td><nuxt-link :to="`/items/${currentDropItem.flyffdb_meta_id}`">{{ currentDropItem.name.en }}</nuxt-link></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </template>
      
      <template v-if="dropItems.uncommon.length">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Uncommon item drops</h6>
            </div>
            <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="currentDropItem in dropItems.uncommon" :key="currentDropItem.id">
                    <td><img :src="`https://gecko-images.flyffdb.info/image/item/${currentDropItem.icon}`"/></td>
                    <td><nuxt-link :to="`/items/${currentDropItem.flyffdb_meta_id}`">{{ currentDropItem.name.en }}</nuxt-link></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </template>

      <template v-if="dropItems.common.length">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Common item drops</h6>
            </div>
            <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="currentDropItem in dropItems.common" :key="currentDropItem.id">
                    <td><img :src="`https://gecko-images.flyffdb.info/image/item/${currentDropItem.icon}`"/></td>
                    <td><nuxt-link :to="`/items/${currentDropItem.flyffdb_meta_id}`">{{ currentDropItem.name.en }}</nuxt-link></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </template>
    </div>
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

    //"common" "uncommon" "rare" "veryrare" "unique"
    let dropItems = {
      common: [],
      uncommon: [],
      rare: [],
      veryrare: [],
      unique: []
    };
    for (const currentDrop of monster.drops) {
      let droppedItem;
      try {
        droppedItem = await $content('items', 'item_' + currentDrop.item).fetch();
        dropItems[droppedItem.rarity].push(droppedItem);
      // OR const article = await $content(`articles/${params.slug}`).fetch()
      } catch (e) {
        return error({ message: 'Dropped Item ' + currentDrop.item + ' not found' })
      }
    }

    return {
      monster,
      dropItems
    }
  },
  head () {
    return {
      title: this.monster.name.en
    }
  }
}
</script>