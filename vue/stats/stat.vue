<template>
  <section class="flex-grow">
    <p class="text-center text-gray-500 pt-8">Loading...</p>
  </section>
</template>

<script>
export default {
  data() {
    return {
      stats: [],
      round: null,
      stat: null,
    };
  },
  methods: {
    fetchStat() {
      console.log(this.$route.params);
      fetch("http://localhost/round/" + this.round + "/stats/" + stat, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          this.stat = res.stat;
          this.round = res.round;
        });
    },
  },
  created() {
    this.fetchStat();
  },
};
</script>