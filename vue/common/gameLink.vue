<template>
  <a class="link" v-if="server.gameLink" :href="server.gameLink"
    ><i class="fa fa-fw fa-gamepad"></i> {{ server.name }}</a
  >
  <span v-else> {{ server.name }}</span>
</template>

<script>
export default {
  props: {
    server: {
      required: true,
      type: Object,
    },
  },
};
</script>