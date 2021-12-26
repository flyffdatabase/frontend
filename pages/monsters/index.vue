<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Monsters</li>
      </ol>
    </nav>

    <div v-for="world in monstersByWorld" :key="world.id">
      <div class="alert alert-success">
        <h3>{{ world.info.name.en }}</h3>
      </div>
      <div v-for="continent in world.continents" :key="continent.id">
        <template v-if="continent.info">
          {{continent.info.name.en}}
        </template>
        <div class="row">
          <nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`" class="col-xl-3 col-md-6 mb-1" v-for="currentMonster in continent.monsters" :key="currentMonster.id">
              <div class="card border-left-primary shadow">
                  <div class="card-body">
                    <div class="user-block">
                      <img  :src="`${currentMonster.icon}`" alt="Monster Icon" style="image-rendering: pixelated;">
                      <span class="username text-muted">{{ currentMonster.name.en }}</span>
                      <span class="description">Level: {{ currentMonster.level }}</span>
                    </div>
                  </div>
              </div>
          </nuxt-link>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let query = $content('monsters', { deep: true })
      .sortBy('level', 'asc')
    const monsters = await query.fetch();
    let monstersByWorld = {};
    for(const currentMonsterIndex in monsters) {
      const currentMonster = monsters[currentMonsterIndex];

      if (!currentMonster.location) continue;
      if (currentMonster.location.continent === undefined) currentMonster.location.continent = 1234;
      

      if (!monstersByWorld.hasOwnProperty(currentMonster.location.world)) {
        monstersByWorld[currentMonster.location.world] = {
          'id': currentMonster.location.world,
          'minlevel': currentMonster.level,
          'info': await $content('worlds', 'world_' + currentMonster.location.world).fetch(),
          'continents': []
        };
      }
      let continent = undefined;
      const continents = monstersByWorld[currentMonster.location.world]['continents'];
      if (!continents.hasOwnProperty(currentMonster.location.continent)) {
        const allContinents = monstersByWorld[currentMonster.location.world].info.continents;

        for(const currentContinent in allContinents) {
          continent = allContinents[currentContinent];
          if (currentMonster.location.continent == currentContinent.id) {
            continents[currentMonster.location.continent] = {
              'id': currentContinent.id,
              'minlevel': currentMonster.level,
              'info': allContinents[currentContinent],
              'monster': []
            };
          }
        }
      }

      try {
        continents[currentMonster.location.continent].monsters.push(currentMonster);

      } catch (error) {
      }
    }
    
    monstersByWorld = Object.values(monstersByWorld);
    monstersByWorld.sort((a, b) => (a.minlevel > b.minlevel) ? 1 : -1);
console.log(monstersByWorld)
    return {
      monstersByWorld
    }
  }
}
</script>