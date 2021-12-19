<template>
  <div>
    <nuxt-link to="/">Home</nuxt-link>
    <h2>flyffdb.info</h2>
    <p>Free online database for the soon to be released game Flyff - Project M.</p>

    <input id="search" v-model="q" placeholder="Search..." />

    <ul>
      <li>
        <nuxt-link to="/items">Items</nuxt-link>
      </li>
      <li>
        <nuxt-link to="/worlds">Worlds</nuxt-link>
      </li>
    </ul>
  </div>
</template>
