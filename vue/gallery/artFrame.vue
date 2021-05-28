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
        <star-rating
          v-model:rating="rating"
          :show-rating="false"
          :star-size="20"
          :inline="true"
          @rating-selected="castVote"
        />
      </div>
    </div>
  </div>
</template>
<script>
import StarRating from "vue-star-rating";

export default {
  components: {
    StarRating,
  },
  props: {
    title: "",
    md5: "",
    url: "",
    ckey: "",
    votes: 0,
    rating: 0,
  },
  methods: {
    castVote(rating) {
      this.votes++;
      console.log(this);
      console.log(rating);
    },
  },
};
</script>
