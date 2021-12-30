<template>
  <div class="pt-3">
    <div class="row">
      <div class="col-sm-6">
        <h1>{{ monster.name.en }}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
          <li class="breadcrumb-item"><nuxt-link to="/monsters">Monsters</nuxt-link></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div><!-- /.col -->
    </div>
    <div class="row">
      <div class="col-xl-3 col-md-4 mb-1">
        <div class="card shadow ">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="card-title">Monster Details</h6>
            </div>
            <div class="card-body p-4" :style="`background-image: url('${monster.icon}');box-sizing: content-box; background-repeat: no-repeat;background-position: center center;background-size: auto;image-rendering: pixelated;min-height: 10em;`">
                
            </div>
            <div class="card-body">
              <table class="table table-striped borderless">
                <thead>
                  <tr><th colspan="2">Data</th></tr>
                </thead>
                <tbody>
                  <tr><td>Level: </td><td>{{ monster.level }}</td></tr>
                  <tr><td>HP: </td><td>{{ monster.hp | thousands }}</td></tr>
                  <tr><td>Defense: </td><td>{{ monster.defense | thousands }}</td></tr>
                </tbody> 
              </table>
            </div>
        </div>
      </div>
      <div class="col-xl-9 col-md-8 mb-8">
        <template v-if="monster.experienceTable.length > 0">
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title">Experience by Level</h6>
                </div>
                <div class="card-body pt-0">
                  <ExpChart :data="monster.experienceTable" :height="200" />
                </div>
              </div>
            </div>
          </div>
        </template>
        <h2>Drop Information</h2>
        <div class="row">
          <div class="col-xl-6 col-lg-12 col-md-12">
            <div class="card border-left-primary shadow">
              <div class="card-body">
                <div class="user-block">
                  <img src="/images/perin.png" stlye="image-rendering: pixelated" alt="Penya Icon">
                  <span class="username text-muted pt-2">{{ monster.minDropGold | thousands }} - {{ monster.maxDropGold | penya }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <template v-if="dropItems.unique.length">
            <div class="col-xl-6 col-lg-12 col-md-12 mb-1">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="card-title">Unique item drops</h6>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.unique" :key="currentDropItem.item.id">
                        <td><ItemDisplay :for="currentDropItem.item"/></td>
                        <td style="vertical-align: middle; text-align: center; white-space: nowrap">{{ currentDropItem.dropInfo.probabilityRange | probabilityRange }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
          
          <template v-if="dropItems.veryrare.length">
            <div class="col-xl-6 col-lg-12 col-md-12 mb-1">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="card-title">Very Rare item drops</h6>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.veryrare" :key="currentDropItem.item.id">
                        <td><ItemDisplay :for="currentDropItem.item"/></td>
                        <td style="vertical-align: middle; text-align: center; white-space: nowrap">{{ currentDropItem.dropInfo.probabilityRange | probabilityRange }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
          
          <template v-if="dropItems.rare.length">
            <div class="col-xl-6 col-lg-12 col-md-12 mb-1">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="card-title">Rare item drops</h6>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.rare" :key="currentDropItem.item.id">
                        <td><ItemDisplay :for="currentDropItem.item"/></td>
                        <td style="vertical-align: middle; text-align: center; white-space: nowrap">{{ currentDropItem.dropInfo.probabilityRange | probabilityRange }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
          
          <template v-if="dropItems.uncommon.length">
            <div class="col-xl-6 col-lg-12 col-md-12 mb-1">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="card-title">Uncommon item drops</h6>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.uncommon" :key="currentDropItem.item.id">
                        <td><ItemDisplay :for="currentDropItem.item"/></td>
                        <td style="vertical-align: middle; text-align: center; white-space: nowrap">{{ currentDropItem.dropInfo.probabilityRange | probabilityRange }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>

          <template v-if="dropItems.common.length">
            <div class="col-xl-6 col-lg-12 col-md-12 mb-1">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="card-title">Common item drops</h6>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.common" :key="currentDropItem.item.id">
                        <td><ItemDisplay :for="currentDropItem.item"/></td>
                        <td style="vertical-align: middle; text-align: center; white-space: nowrap">{{ currentDropItem.dropInfo.probabilityRange | probabilityRange }}</td>
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
  async asyncData ({ $http, params, error }) {
    return await $http.$get(`/api/monsters/${params.slug}`);
  },
  head () {
    return {
      title: this.monster.name.en
    }
  }
}
</script>