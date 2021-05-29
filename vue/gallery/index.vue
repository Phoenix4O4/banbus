<template>
  <div class="grid grid-cols-3 gap-4">
    <div v-for="(art, title) in artwork" v-bind:key="title">
      <h2 class="text-xl font-bold mb-4">{{ title }}</h2>
      <artFrame v-for="a in art" :key="a.md5" v-bind="a"></artFrame>
    </div>
  </div>
</template>

<script>
const initialTicketUrl = "?json=true";
const pollUrl = "/tgdb/ticket/live/poll/?json=true";
const serverUrl = "https://tgstation13.org/dynamicimages/serverinfo.json";
import userBadge from "./../common/userBadge.vue";
import gameLink from "./../common/gameLink.vue";
import artFrame from "./artFrame.vue";

import moment from "moment";

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
          console.log(res);
        });
    },
    castVote(rating) {
      console.log(rating);
    },
    pollForTickets() {
      this.changeMessage("Checking for new tickets...");
      this.lastId = document.getElementsByClassName("ticket")[0].id;
      fetch(pollUrl, {
        method: "POST",
        mode: "no-cors",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          lastId: this.lastId,
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          this.canBwoink = false;
          for (const [k, v] of Object.entries(res.tickets)) {
            for (const [key, value] of Object.entries(this.servers)) {
              if (v.server.port == value.serverdata.port) {
                if (!value.toggled) {
                  v.hide = true;
                  console.log(
                    `This is a ticket for ${value.serverdata.servername}, but this server is not toggled so we are hiding this ticket`
                  );
                } else {
                  this.canBwoink = true;
                }
              }
            }
            if (this.newTickets && "Ticket Opened" != v.action) {
              v.hide = true;
              this.canBwoink = false;
              console.log(
                `Only polling for new tickets. This is not a new ticket, so we are hiding it.`
              );
            }
          }
          if (this.canBwoink) {
            this.bwoink();
          }
          this.tickets = [...res.tickets, ...this.tickets];
          this.changeMessage(res.messages[0].text);
        });
    },
  },
  mounted() {
    this.getCurrentServer();
    this.fetchServerGallery(this.server);
  },
  created: function () {
    this.moment = moment;
  },
};
</script>