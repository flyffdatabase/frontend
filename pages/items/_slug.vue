<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/items">Items</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
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

    <div class="row">
      <template v-if="droppingMonsters.length">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card shadow">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Dropped by monsters</h6>
            </div>
            <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
              <table class="table table-striped">
                <tbody>
                  <tr v-for="currentMonster in droppingMonsters" :key="currentMonster.id">
                    <td><img height="30px" :src="`https://gecko-images.flyffdb.info/image/monster/${currentMonster.icon}`"/></td>
                    <td><nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`">{{ currentMonster.name.en }}</nuxt-link></td>
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
    let item
    try {
      item = await $content('items', params.slug).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      return error({ message: 'Item ' + params.slug + ' not found' })
    }

    let droppingMonsters = [];
    for (const currentMonsterId of item.flyffdb_dropped_by) {
      let monster;
      try {
        monster = await $content('monsters', 'monster_' + currentMonsterId).fetch();
        droppingMonsters.push(monster);
      // OR const article = await $content(`articles/${params.slug}`).fetch()
      } catch (e) {
        return error({ message: 'Monster ' + currentMonsterId + ' not found' })
      }
    }
    return {
      item,
      droppingMonsters
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