<template>
  <div>
    <span class="text-2xl font-bold mt-4 block"
      >{{ rating
      }}<span class="text-gray-300 text-xl"
        >/5
        <span v-if="votes" :title="votes + ' Votes'">({{ votes }})</span></span
      ></span
    >
    <Rating
      v-model="rating"
      @change="castVote"
      :disabled="disabled"
      :cancel="false"
    />
  </div>
</template>

<script>
export default {
  setup() {},
  components: {
    Rating,
  },
};
</script>