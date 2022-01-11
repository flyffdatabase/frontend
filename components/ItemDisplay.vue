<template>
  <nuxt-link :to="`/items/${item.flyffdb_meta_id}`" class="user-block">
    <img style="image-rendering: pixelated" :src="`${item.icon}`" alt="Item Icon" />
    <span class="username text-white">
        {{ item.name.en }}
        <template v-if="item.sex == 'male'"><i class="fas fa-mars"></i></template>
        <template v-if="item.sex == 'female'"><i class="fas fa-venus"></i></template>
    </span>
    <span class="description text-muted">
      <template v-if="item.category != 'weapon' && item.category != 'armor'">
        {{ item.raw_description.en }}
      </template>
      <template v-if="item.category == 'weapon' || item.category == 'armor'">
        Level: {{ item.level }}
      </template>
      <template v-if="item.category == 'weapon'">
        Atk: {{ item.minAttack }} - {{ item.maxAttack }}
      </template>
      <template v-if="item.category == 'armor'">
        Def: {{ item.minDefense }} - {{ item.maxDefense }}
      </template>
    </span>
  </nuxt-link>
</template>

<script>
export default {
  data () {
    let item = this.$attrs.for;
    return {
      item
    }
  }
}
</script>