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
        <div class="row">
          <div id="map-wrap" class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title">Flaris</h6>
              </div>
              <div class="card-body" style="height: 800px">
                <client-only>
                  <template v-for="currentWorld in spawnWorlds">
                    <l-map ref="map" :crs="crs" :options="{zoomControl: true, minZoom: -10, zoom: -1}" :key="currentWorld.worldId">
                      
                      <l-image-overlay v-for="currentTile in currentWorld.tiles" 
                        :url="currentTile.url" 
                        :bounds="currentTile.bounds" 
                        :key="currentTile.id"/>

                      <l-marker v-for="star in stars" :lat-lng="star" :key="star.name">
                        <l-popup :content="star.name"/>
                      </l-marker>
                    </l-map>
                  </template>
                </client-only>
              </div>
            </div>
          </div>
        </div>
        <template v-if="monster.experienceTable.length > 0">
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title">Experience by Level</h6>
                </div>
                <div class="card-body pt-0">
                  <ExpChart :data="monster.experienceTable" :height="200" :rangeLevel="60" :rangeMiddle="monster.level" />
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
  mounted( ) {
    let leaflet = require('leaflet');
    this.crs = leaflet.CRS.Simple;
    let initialized = false;
    /*
    let tryAgain = () => {};
    tryAgain = ()=>{
      let self = this;
      if (this.$refs.map.mapObject) {
        this.$refs.map.mapObject.setView([70, 120], 1);
      } else {
        this.$nextTick(tryAgain);
      }
    };
    this.$nextTick(tryAgain);*/
  },
  async asyncData ({ $http, $content, params, error }) {
    let monsterData = await $http.$get(`/api/monsters/${params.slug}`);

    let spawnWorlds = {};
    let mapScale = 1;

    for(const currentLocation of monsterData.monster.spawns) {
      if (!spawnWorlds.hasOwnProperty(currentLocation.world)) {
        let currentWorld = await $content('worlds', 'world_' + currentLocation.world).fetch();
        spawnWorlds[currentWorld.id] = {
          worldId: currentWorld.id,
          world: currentWorld,
          spawns: [],
          tiles: []
        };

        for(let x = 0; x < currentWorld.width; x = x +  currentWorld.tileSize) {
          for(let y = 0; y < currentWorld.height; y = y + currentWorld.tileSize) {
            spawnWorlds[currentWorld.id].tiles.push({
              url: 'https://ik.imagekit.io/flyffdb/icon/world/' + currentWorld.tileName + (x / currentWorld.tileSize) + '-' + (y / currentWorld.tileSize) + '-0.png',
              bounds: [
                [(y / mapScale) * -1, x / mapScale], 
                [(y / mapScale + currentWorld.tileSize / mapScale) * -1, 
                x / mapScale + currentWorld.tileSize / mapScale]],
              id:'key'+currentWorld.id+'_'+x+'-'+y
            });
          }
        }
      }
      
    }

    monsterData.spawnWorlds = Object.values(spawnWorlds);

    monsterData.crs = undefined;
    monsterData.stars = [
        { name: 'Sol', lng: 175.2, lat: 145.0 },
        { name: 'Mizar', lng: 41.6, lat: 130.1 },
        { name: 'Krueger-Z', lng: 13.4, lat: 56.5 },
        { name: 'Deneb', lng: 218.7, lat: 8.3 }
      ];
    if (process.client) {
      console.log(monsterData);
    }
    return monsterData;
  },
  head () {
    return {
      title: this.monster.name.en
    }
  }
}
</script>