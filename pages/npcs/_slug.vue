<template>
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><nuxt-link to="/">Home</nuxt-link></li>
        <li class="breadcrumb-item"><nuxt-link to="/npcs">NPCs</nuxt-link></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
      </ol>
    </nav>

    <h2>{{ npcInfo.name.en }}</h2>

    <template v-if="npcInfo.shop">
      <h3>Available in Shop at this NPC</h3>
    </template>
  </div>
</template>

<script>
export default {
  async asyncData({ $content, params, error }) {
    let npcInfo;
    try {
      npcInfo = await $content("npcs", params.slug).fetch();
    } catch (e) {
      return error({ message: "NPC " + params.slug + " not found" });
    }
    return {
      npcInfo
    };
  },
  head() {
    return {
      title: this.item.name.en,
    };
  },
};
</script>