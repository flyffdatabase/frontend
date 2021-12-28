<template>
  <div>
    <h2>flyffdb.info</h2>
    <p>Free online database for the soon to be released game Flyff - Project M.</p>

    <div class="row">
      <nuxt-link to="/items" class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Item database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ itemCount | thousands }} items</div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/npcs" class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              NPC Database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ npcCount }} npcs</div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/skills" class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Skill Database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ skillCount }} skills</div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/quests" class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Quest Database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ questCount }} quests</div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/monsters" class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Monster Database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ monsterCount }} monsters</div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/achievements" class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                              Achievement Database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ achievementCount }} achievements</div>
                      </div>
                      <div class="col-auto">
                          <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, route }) {
    let itemCount = (await $content('items').only([]).fetch()).length;
    let npcCount = (await $content('npcs').only([]).fetch()).length;
    let skillCount = (await $content('skills').only([]).fetch()).length;
    let questCount = (await $content('quests').only([]).fetch()).length;
    let monsterCount = (await $content('monsters').only([]).fetch()).length;
    let achievementCount = (await $content('achievements').only([]).fetch()).length;
    return {
      itemCount,
      npcCount,
      skillCount,
      questCount,
      monsterCount,
      achievementCount
    }
  }
}
</script>