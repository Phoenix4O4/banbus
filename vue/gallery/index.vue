<template>
  <div class="grid grid-cols-3 gap-4">
    <div v-for="(art, title) in artwork" v-bind:key="title">
      <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
      <artFrame
        v-for="a in art"
        :key="a.md5"
        v-bind="a"
        v-model:rating="a.rating"
        v-model:votes="a.votes"
      ></artFrame>
    </div>
  </div>
</template>

<script>
import userBadge from "./../common/userBadge.vue";
import gameLink from "./../common/gameLink.vue";
import artFrame from "./artFrame.vue";

export default {
  components: {
    userBadge,
    gameLink,
    artFrame,
  },
  data() {
    return {
      artwork: [],
      server: "",
    };
  },
  methods: {
    getCurrentServer() {
      var currentUrl = window.location.pathname;
      this.server = currentUrl.split("/")[2];
    },
    fetchServerGallery(server) {
      fetch(`/gallery/${server}?json=true`, {
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          this.artwork = res.art;
          console.log(this.artwork);
        });
    },
    updateArtworkRating(rating, votes, md5) {
      console.log(this);
      console.log(this.rating, this.votes);
      this.rating = rating;
      this.votes = votes;
    },
  },
  mounted() {
    this.getCurrentServer();
    this.fetchServerGallery(this.server);
  },
};
</script>