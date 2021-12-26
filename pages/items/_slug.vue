<template>
  <div class="pt-3">
    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Item Details</h3>
            </div>
            <div class="card-body p-4" :style="`background-image: url('${item.icon}');background-repeat: no-repeat;background-position: center center;background-size: 8em auto;image-rendering: pixelated;min-height: 10em;`">
                
            </div>
            <div class="card-body">
              <table class="table table-striped borderless">
                <thead>
                  <tr><th colspan="2">Data</th></tr>
                </thead>
                <tbody>
                  <template v-if="item.category == 'weapon'">
                    <tr><td>Atk</td><td>{{ item.minAttack }} - {{ item.maxAttack}}</td></tr>
                  </template>
                  <tr><td>Buy Price: </td><td>{{ item.sellPrice | penya }}</td></tr>
                  <tr><td>Sell Price: </td><td>{{ item.sellPrice / 4 | penya }}</td></tr>
                  <template v-if="item.abilities">
                    <tr>
                      <td>Abilities</td>
                      <td>
                        <ul>
                          <li v-for="itemability in item.abilities">
                              {{ itemability.parameter }}
                          </li>
                        </ul>
                      </td>
                    </tr>
                  </template>
                </tbody> 
              </table>

              <template v-if="!item.tradable">
                <div class="card bg-danger text-white shadow">
                  <div class="card-body">
                    This item can <b>NOT</b> be traded!
                  </div>
                </div>
              </template>
              
            </div>
        </div>
      </div>
      <div class="col-xl-9 col-md-6 mb-8">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ item.name.en }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
              <li class="breadcrumb-item"><nuxt-link to="/items">Items</nuxt-link></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div><!-- /.col -->
        </div>

        <template v-if="!item.raw_description.en || item.raw_description.en == 'null'">
          <div class="card bg-info text-white shadow mb-4">
            <div class="card-body">
              This item has no description!
            </div>
          </div>
        </template>
        <template v-if="item.raw_description.en && item.raw_description.en != 'null'">
          <div class="card bg-info text-white shadow mb-4">
            <div class="card-body">
              <b>Description:</b> {{ item.raw_description.en }}
            </div>
          </div>
        </template>

        <div class="row">
          <template v-if="droppingMonsters.length">
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Dropped by monsters</h3>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentMonster in droppingMonsters" :key="currentMonster.id">
                        <td>
                          <nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`" class="user-block">
                            <img style="image-rendering: pixelated" :src="`${currentMonster.icon}`" alt="Monster Icon">
                            <span class="username text-muted">{{ currentMonster.name.en }}</span>
                            <span class="description">Level: {{ currentMonster.level }}</span>
                          </nuxt-link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
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
    droppingMonsters.sort((a, b) => (a.level > b.level) ? 1 : -1)
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