<template>
  <aside
    id="sidebar"
    class="flex-shrink-0 border-r border-gray-300 dark:border-gray-700 w-64 min-h-screen dark:bg-gray-800 overflow-x-hidden overflow-y-scroll py-4"
  >
    <h2
      v-if="stats && round"
      class="text-center block font-bold text-xl mb-4 dark:bg-gray-700"
    >
      <small class="text-sm block">Available Stats For </small>
      <i class="fas fa-circle"></i> {{ round }}
    </h2>
    <p v-else class="text-center text-gray-500 pt-8">Loading...</p>
    <ul v-if="stats" class="font-mono text-sm">
      <li v-for="s in stats" :key="s.id">
        <router-link
          class="px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-500 block"
          :to="`/round/${round}/stat/${s.key_name}`"
          >{{ s.key_name }}</router-link
        >
      </li>
    </ul>
    <p v-else: class="text-center text-gray-500 pt-8">Loading...</p>
  </aside>
</template>

<script>
export default {
  data() {
    return {
      stats: [],
      round: this.$route.params.round,
      stat: null,
    };
  },
  methods: {
    fetchStatList() {
      fetch(
        `http://localhost/round/${this.$route.params.round}/stats/listing`,
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((res) => res.json())
        .then((res) => {
          console.log(res);
          this.stats = res.stats;
        });
    },
    created() {
      this.fetchStatList();
    },
  },
};
</script>