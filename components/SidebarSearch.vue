<template>
  <div class="form-inline" v-bind:class="{ 'sidebar-search-open': searchOpen }">
    <div class="input-group sticky-top" data-widget="sidebar-search">
      <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" v-model="searchTerm" v-on:input="search">
      <div class="input-group-append">
        <button class="btn btn-sidebar">
          <i class="fas fa-search fa-fw"></i>
        </button>
      </div>
    </div>
    <template :v-if="searchOpen">
      <div class="sidebar-search-results">
        <div class="list-group">
          <div v-for="item in results" :key="item._id">
            <a href="#" class="list-group-item">
              <div class="search-title">
                <div class="row">
                <ItemDisplay :for="item._source"/>
                </div>
              </div>
              <div class="search-path"></div>
            </a>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchTerm: '',
      searchTermLength: 0,
      searchOpen: false,
      results: []
    }
  },
  methods: {
    async search({ $http }) {
      this.searchTermLength = this.searchTerm.length;
      this.searchOpen = (this.searchTermLength > 3);
      if (this.searchOpen) {
        let results = await fetch(`http://mvs-1.flyffdb.info:9501/` + this.searchTerm).then(response => response.json());
        this.results = results.hits.hits;
        console.log(results);
      }
    }
  }
}
</script>