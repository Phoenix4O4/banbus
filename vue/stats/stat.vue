<template>
  <section class="flex-grow p-4">
    <h2 v-if="stat" class="block font-bold text-xl mb-4 font-mono">
      <small class="text-sm block">#{{ stat.id }}</small>
      {{ stat.key_name }}
      <small class="text-xs text-gray-500"
        >v.{{ stat.version }} type:{{ stat.key_type }}</small
      >
    </h2>
    <p v-else: class="text-center text-gray-500 pt-8">Loading...</p>
    <div v-if="'associative' === stat.key_type">
      <associative :chartdata="stat.json"></associative>
    </div>
  </section>
</template>

<script>
import associative from "./types/associative";

export default {
  components: {
    associative,
  },
  data() {
    return {
      stats: [],
      round: this.$route.params.round,
      stat: this.$route.params.stat,
    };
  },
  methods: {
    fetchStat() {
      fetch(
        `http://localhost/round/${this.$route.params.round}/stat/${this.$route.params.stat}?json=true`,
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((res) => res.json())
        .then((res) => {
          this.stat = res.stat;
          this.round = res.round;
        });
    },
  },
  created() {
    this.fetchStat();
    this.$watch(
      () => this.$route.params,
      () => {
        this.fetchStat();
      }
    );
  },
  updated() {
    console.log(this.stat);
  },
};
</script>