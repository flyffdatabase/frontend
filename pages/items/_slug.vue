<template>
  <div class="pt-3">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-0">{{ item.name.en }}
          <template v-if="item.sex == 'male'"><i class="fas fa-mars"></i></template>
          <template v-if="item.sex == 'female'"><i class="fas fa-venus"></i></template>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
          <li class="breadcrumb-item"><nuxt-link to="/items">Items</nuxt-link></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div><!-- /.col -->
    </div>
    <div class="row">
      <div class="col-xl-3 col-md-4 mb-1">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="card-title">Item Details</h6>
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
                        <ul class="mb-0 pl-4">
                          <li v-for="itemability in item.abilities" :key="itemability.parameter">
                            {{ itemability.parameter | ability }}

                            <template v-if="itemability.add">
                              + {{ itemability.add }}<template v-if="itemability.rate">%</template>
                            </template>
                            
                          </li>
                        </ul>
                      </td>
                    </tr>
                  </template>
                </tbody> 
              </table>

              <template v-if="!item.tradable">
                <div class="card bg-danger text-white shadow mb-1">
                  <div class="card-body">
                    This item can <b>NOT</b> be traded!
                  </div>
                </div>
              </template>
              
            </div>
        </div>
      </div>
      <div class="col-xl-9 col-md-8 mb-8">
        <h2>Description</h2>
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
              {{ item.raw_description.en }}
            </div>
          </div>
        </template>
        
        <h2>Obtainable Ingame</h2>
        <div class="row">
          <template v-if="droppingMonsters.length">
            <div class="col-xl-6 col-md-6">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title">Drop from Monsters</h6>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentMonster in droppingMonsters" :key="currentMonster.id">
                        <td>
                          <nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`" class="user-block">
                            <img :src="`${currentMonster.icon}?tr=w-100,h-100,cm-pad_resize`" alt="Monster Icon">
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
        <h3>Something else</h3>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData ({ $http, params, error }) {
    return await $http.$get(`/api/items/${params.slug}`);
  },
  head () {
    return {
      title: this.item.name.en,
      description: this.item.raw_description.en
    }
  }
}
</script>