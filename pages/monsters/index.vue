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
      <template v-if="world.monsters">
        <div class="row">
          <nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`" class="col-xl-3 col-md-6 mb-1" v-for="currentMonster in world.monsters" :key="currentMonster.id">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                  <div class="user-block">
                    <img :src="`${currentMonster.icon}?tr=w-100,h-100,cm-pad_resize`" alt="Monster Icon">
                    <span class="username text-muted">{{ currentMonster.name.en }}</span>
                    <span class="description">Level: {{ currentMonster.level }}</span>
                  </div>
                </div>
            </div>
          </nuxt-link>
        </div>
      </template>
      <div v-for="continent in world.continents" :key="continent.id">
        <template v-if="continent.info">
          <h4>{{continent.info.name.en}}</h4>
        </template>
        <div class="row">
          <nuxt-link :to="`/monsters/${currentMonster.flyffdb_meta_id}`" class="col-xl-3 col-md-6 mb-1" v-for="currentMonster in continent.monsters" :key="currentMonster.id">
              <div class="card border-left-primary shadow">
                  <div class="card-body">
                    <div class="user-block">
                      <img :src="`${currentMonster.icon}?tr=w-100,h-100,cm-pad_resize`" alt="Monster Icon">
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
      

      if (!monstersByWorld.hasOwnProperty(currentMonster.location.world)) {
        monstersByWorld[currentMonster.location.world] = {
          'id': currentMonster.location.world,
          'minlevel': currentMonster.level,
          'info': await $content('worlds', 'world_' + currentMonster.location.world).fetch(),
          'continents': [],
          'monsters': []
        };
      }
      
      if (!currentMonster.location.continent) {
        monstersByWorld[currentMonster.location.world].monsters.push(currentMonster);
      } else {
        for(const currentContinentIndex in monstersByWorld[currentMonster.location.world].info.continents) {
          const currentContinent = monstersByWorld[currentMonster.location.world].info.continents[currentContinentIndex];
          
          if (currentContinent.id == currentMonster.location.continent) {
            let alreadyFoundExisting = false;

            for(const currentExistingContinentIndex in monstersByWorld[currentMonster.location.world].continents) {
              if (monstersByWorld[currentMonster.location.world].continents[currentExistingContinentIndex].id == currentContinent.id) {
                
                monstersByWorld[currentMonster.location.world].continents[currentExistingContinentIndex].monsters.push(currentMonster);
                alreadyFoundExisting = true;
              }
            }

            if (!alreadyFoundExisting) {
              monstersByWorld[currentMonster.location.world].continents.push({
                'id': currentContinent.id,
                'minlevel': currentMonster.level,
                'info': currentContinent,
                'monsters': [currentMonster]
              });
            }
          }
        }
      }
    }
    
    monstersByWorld = Object.values(monstersByWorld);
    monstersByWorld.sort((a, b) => (a.minlevel > b.minlevel) ? 1 : -1);

    return {
      monstersByWorld
    }
  }
}
</script>