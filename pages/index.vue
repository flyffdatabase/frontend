<template>
  <div class="pt-3">
    <h2>flyffdb.info</h2>
    <p>Free online database for the soon to be released game Flyff - Project M.</p>

    <div class="row">
      <nuxt-link to="/items" class="col-xl-3 col-md-6">
          <div class="card border-left-primary shadow">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase">
                              Item database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ itemCount | thousands }} items</div>
                      </div>
                      <div class="col-auto">
                          <img style="width: 4em; height: 4em" src="https://ik.imagekit.io/flyffdb/icon/item/syssysscrsmepro.png"/>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/npcs" class="col-xl-3 col-md-6">
          <div class="card border-left-primary shadow">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase">
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

      <nuxt-link to="/skills" class="col-xl-3 col-md-6">
          <div class="card border-left-primary shadow">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase">
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

      <nuxt-link to="/quests" class="col-xl-3 col-md-6">
          <div class="card border-left-primary shadow">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase">
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

      <nuxt-link to="/monsters" class="col-xl-3 col-md-6">
          <div class="card border-left-primary shadow">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase">
                              Monster Database</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ monsterCount }} monsters</div>
                      </div>
                      <div class="col-auto">
                          <img style="width: 4em; height: 4em" src="https://ik.imagekit.io/flyffdb/icon/monster/pukepuke.png?tr=w-100,h-100,cm-pad_resize"/>
                      </div>
                  </div>
              </div>
          </div>
      </nuxt-link>

      <nuxt-link to="/achievements" class="col-xl-3 col-md-6">
          <div class="card border-left-primary shadow">
              <div class="card-body">
                  <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-primary text-uppercase">
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