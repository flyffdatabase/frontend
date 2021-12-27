<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/monsters">Monsters</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
      </ol>
    </nav>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">

        <div class="card shadow ">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Monster Details</h6>
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
      <div class="col-xl-9 col-md-6 mb-8">
        <h2>{{ monster.name.en }}</h2>
        <h3>Drop Information</h3>
        <div class="row">
          <template v-if="dropItems.unique.length">
            <div class="col-xl-4 col-lg-6 col-sm-12 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h3 class="card-title">Unique item drops</h3>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.unique" :key="currentDropItem.id">
                        <td><ItemDisplay :for="currentDropItem"/></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
          
          <template v-if="dropItems.veryrare.length">
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h3 class="card-title">Very Rare item drops</h3>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.veryrare" :key="currentDropItem.id">
                        <td><ItemDisplay :for="currentDropItem"/></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
          
          <template v-if="dropItems.rare.length">
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h3 class="card-title">Rare item drops</h3>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.rare" :key="currentDropItem.id">
                        <td><ItemDisplay :for="currentDropItem"/></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>
          
          <template v-if="dropItems.uncommon.length">
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h3 class="card-title">Uncommon item drops</h3>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.uncommon" :key="currentDropItem.id">
                        <td><ItemDisplay :for="currentDropItem"/></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </template>

          <template v-if="dropItems.common.length">
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h3 class="card-title">Common item drops</h3>
                </div>
                <div class="card-body p-0" style="max-height: 15em;height:15em; overflow-y: auto">
                  <table class="table table-striped">
                    <tbody>
                      <tr v-for="currentDropItem in dropItems.common" :key="currentDropItem.id">
                        <td><ItemDisplay :for="currentDropItem"/></td>
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