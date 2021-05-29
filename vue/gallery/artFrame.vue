<template>
  <div
    class="border border-gray-300 dark:border-gray-700 mb-4 flex justify-between p-4 rounded-md"
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
        <Rating
          v-model="rating"
          @change="castVote"
          :disabled="disabled"
          :cancel="false"
        />
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
    ckey: { type: String, default: "Player", required: true },
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
  methods: {
    castVote(event) {
      this.disabled = true;
      console.log(event.value);
      console.log(this.md5);
      console.log(this.server);
    },
  },
};
</script>
