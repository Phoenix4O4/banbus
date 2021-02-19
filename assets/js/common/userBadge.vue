<template>
  <span
    class="badge"
    :style="{
      backgroundColor: user.rank.backColor,
      color: user.rank.foreColor,
    }"
  >
    <i class="fa fa-fw" :class="['fa-' + user.rank.icon]"></i>
    {{ user.displayName }}
  </span>
</template>

<script>
export default {
  props: {
    user: {
      required: true,
      type: Object,
    },
  },
};
</script>