<template>
  <div
    class="
      border border-gray-300
      dark:border-gray-700
      mb-4
      flex
      justify-between
      p-4
      rounded-md
    "
  >
    <img :src="url" style="width: 128px; height: 128px" class="pixel-img" />
    <div class="pl-4 text-right">
      <span class="font-bold block leading-tight">{{ title }}</span>
      <span v-if="ckey">by {{ ckey }}</span>
      <div>
        <span class="text-2xl font-bold mt-4 block"
          >{{ rating
          }}<span class="text-gray-300 text-xl"
            >/5
            <span v-if="votes" :title="votes + ' Votes'"
              >({{ votes }})</span
            ></span
          ></span
        >
        <Rating @change="castVote" :disabled="disabled" :cancel="false" />
      </div>
    </div>
  </div>
</template>
<script>
import Rating from "primevue/rating";

export default {
  components: {
    Rating,
  },
  props: {
    title: {
      type: String,
      default: "A work of art",
      required: true,
    },
    md5: {
      type: String,
      default: "00000000000000000000000000000000",
      required: true,
    },
    url: {
      type: String,
      default: "",
    },
    ckey: { type: String, default: "Player", required: false },
    votes: {
      type: Number,
      default: 0,
      required: false,
    },
    rating: {
      type: Number,
      default: 0,
      required: false,
    },
  },
  data() {
    return {
      disabled: false,
      server: this.$parent.server,
    };
  },
  // computed: {
  //   rating: {
  //     get: function () {
  //       return this.rating;
  //     },
  //     set: function (newRating) {
  //       this.rating = newRating;
  //     },
  //   },
  //   votes: {
  //     get: function () {
  //       return this.votes;
  //     },
  //     set: function (newVotes) {
  //       this.votes = newVotes;
  //     },
  //   },
  // },
  emits: ["update:rating", "update:votes"],
  methods: {
    castVote(event) {
      this.disabled = true;
      fetch(`/gallery/vote`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          rating: event.value,
          md5: this.md5,
          server: this.server,
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          this.$emit("update:rating", res.art.rating);
          this.$emit("update:votes", res.art.votes);
        });
      this.disabled = false;
    },
  },
};
</script>
