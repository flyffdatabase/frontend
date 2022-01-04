<template>
  <div class="pt-1">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-0">{{ quest.name.en }}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
          <li class="breadcrumb-item"><nuxt-link to="/quests">Quests</nuxt-link></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div><!-- /.col -->
    </div>
    <div class="row">
      <div class="col-xl-3 col-md-4 mb-1">
        <div class="card shadow">
            <div class="card-header py-3 pt-0 d-flex flex-row align-items-center justify-content-between">
                <h6 class="card-title">Quest Details</h6>
            </div>
            <div class="card-body pt-0">
              <table class="table table-striped borderless">
                <thead>
                  <tr><th colspan="2">Data</th></tr>
                </thead>
                <tbody>
                  <tr><td>Level: </td><td>{{ quest.minLevel }} - {{ quest.maxLevel }}</td></tr>
                  <tr><td>Type: </td><td>{{ quest.type }}</td></tr>
                </tbody> 
              </table>
            </div>
        </div>
      </div>
      <div class="col-xl-9 col-md-8 mb-8">
        <h2>Description</h2>
        <template v-if="!quest.raw_description.en || quest.raw_description.en == 'null'">
          <div class="card bg-info text-white shadow mb-4">
            <div class="card-body">
              This quest has no description!
            </div>
          </div>
        </template>
        <template v-if="quest.raw_description.en && quest.raw_description.en != 'null'">
          <div class="card bg-info text-white shadow mb-4">
            <div class="card-body">
              {{ quest.raw_description.en }}
            </div>
          </div>
        </template>

        <div class="row">
          <template v-if="beginNpc.name">
            <nuxt-link :to="`/npcs/${beginNpc.flyffdb_meta_id}`" class="col-xl-4 col-md-6 col-sm-12 mb-1">
              <h6 style="color: white">Start NPC</h6>
              <div class="card border-left-primary shadow">
                  <div class="card-body">
                    <div class="user-block">
                      <img :src="`${beginNpc.image}?tr=w-100,h-100,cm-pad_resize`" alt="NPC Icon">
                      <span class="username text-muted">{{ beginNpc.name.en }}</span>
                      <span class="description">put something here?!</span>
                    </div>
                  </div>
              </div>
            </nuxt-link>
          </template>
          <template v-if="endNpc.name">
            <nuxt-link :to="`/npcs/${endNpc.flyffdb_meta_id}`" class="col-xl-4 col-md-6 col-sm-12 mb-1">
              <h6 style="color: white">End NPC</h6>
              <div class="card border-left-primary shadow">
                  <div class="card-body">
                    <div class="user-block">
                      <img :src="`${endNpc.image}?tr=w-100,h-100,cm-pad_resize`" alt="NPC Icon">
                      <span class="username text-muted">{{ endNpc.name.en }}</span>
                      <span class="description">put something here?!</span>
                    </div>
                  </div>
              </div>
            </nuxt-link>
          </template>
        </div>

        <h2>Reward</h2>
        <template v-if="quest.endReceiveExperience">
          <div class="row">
            <template v-if="quest.endReceiveExperience">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h6 class="card-title">Experience by Level</h6>
                  </div>
                  <div class="card-body pt-0">
                    <ExpChart :data="quest.endReceiveExperience" :height="200" />
                  </div>
                </div>
              </div>
            </template>
            <template v-if="quest.endReceiveGold">
              <div class="col-md-6">
                <div class="card border-left-primary shadow">
                  <div class="card-body">
                    <div class="user-block">
                      <img src="/images/perin.png" stlye="image-rendering: pixelated" alt="Penya Icon">
                      <span class="username text-muted pt-2">{{ quest.endReceiveGold | penya }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>

        <h2>Dialogs</h2>
        <div class="card direct-chat direct-chat-primary">
          <div class="card-body">
            <div class="pl-3 pr-3 pt-3 pb-2">
              <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">Quest Start</span>
              </div>
              <div class="direct-chat-msg" v-for="currentDialog in quest.dialogsBegin" :key="currentDialog.en">
                <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                <div class="direct-chat-text">
                  {{ currentDialog.en }}
                </div>
              </div>
              <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">Quest Accept</span>
              </div>
              <div class="direct-chat-msg" v-for="currentDialog in quest.dialogsAccept" :key="currentDialog.en">
                <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                <div class="direct-chat-text">
                  {{ currentDialog.en }}
                </div>
              </div>
              <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">Quest Decline</span>
              </div>
              <div class="direct-chat-msg" v-for="currentDialog in quest.dialogsDecline" :key="currentDialog.en">
                <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                <div class="direct-chat-text">
                  {{ currentDialog.en }}
                </div>
              </div>
              <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">Quest Complete</span>
              </div>
              <div class="direct-chat-msg" v-for="currentDialog in quest.dialogsComplete" :key="currentDialog.en">
                <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                <div class="direct-chat-text">
                  {{ currentDialog.en }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ExpChart from '~/components/ExpChart.vue';

import BarChart from "~/components/ExpChart.vue";
export default {
  components: { ExpChart },
  data() {
    return {
    };
  },
  async asyncData ({ $content, params, error }) {
    let quest;
    let beginNpc;
    let endNpc;
    try {
      quest = await $content('quests', params.slug).fetch();
    } catch (e) {
      return error({ message: 'Quest ' + params.slug + ' not found' })
    }

    if (quest.beginNPC) {
      try {
        beginNpc = await $content('npcs', 'npc_' + quest.beginNPC).fetch();
      } catch (e) {
        return error({ message: 'NPC ' + quest.beginNPC + ' not found' })
      }
    }

    if (quest.endNPC) {
      try {
        endNpc = await $content('npcs', 'npc_' + quest.endNPC).fetch();
      } catch (e) {
        return error({ message: 'NPC ' + quest.endNPC + ' not found' })
      }
    }
    
    return {
      quest,
      beginNpc,
      endNpc
    }
  },
  head () {
    return {
      title: this.quest.name.en
    }
  }
}
</script>