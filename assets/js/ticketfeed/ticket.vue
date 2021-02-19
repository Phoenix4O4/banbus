<template>
  <div>
    <button
      v-on:click="mute"
      class="bg-green-600 text-white block w-full p-2 text-xl font-bold mb-4 hover:bg-green-400 rounded"
    >
      <i
        class="fa fa-fw"
        :class="[muted ? 'fa-volume-mute' : 'fa-volume-up']"
      ></i>
      {{ muted ? "Unmute Sound" : "Mute Sound" }}
    </button>

    <dl
      v-for="t in tickets"
      :key="t.id"
      :id="t.id"
      class="flex mb-4 pb-4 border-b border-gray-300 ticket added"
    >
      <dt class="whitespace-nowrap text-right pr-3 tabular-nums">
        <a class="link" :href="'/tgdb/ticket/' + t.round + '/' + t.ticket"
          >#{{ t.round }}-{{ t.ticket }}</a
        >
        <span class="block text-gray-500 text-xs"
          >{{ t.timestamp }} <br />on {{ t.server.name }}
        </span>
      </dt>
      <dd>
        <span class="whitespace-nowrap">
          <i
            class="fa fa-fw pr-3"
            :class="['fa-' + t.icon, 'text-' + t.class]"
          ></i>
          <strong v-if="t.sender"> {{ t.sender.displayName }}</strong>
          <strong v-else> {{ t.recipient.displayName }}</strong>
        </span>
        {{ t.message }}
      </dd>
    </dl>
  </div>
</template>

<script>
const initialTicketUrl = "?json=true";
const pollUrl = "/tgdb/ticket/live/poll/?json=true";
export default {
  data() {
    return { tickets: [], lastId: 0, muted: true };
  },
  methods: {
    mute() {
      this.muted = !this.muted;
    },
    fetchInitialTickets() {
      fetch(initialTicketUrl, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((res) => res.json())
        .then((res) => {
          this.tickets = res.tickets;
        });
    },
    pollForTickets() {
      this.lastId = document.getElementsByClassName("ticket")[0].id;
      fetch(pollUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ lastId: this.lastId }),
      })
        .then((res) => res.json())
        .then((res) => {
          if (0 < res.tickets.length) {
            this.bwoink();
          }
          this.tickets = [...res.tickets, ...this.tickets];
        });
    },
    bwoink() {
      if (!this.muted) {
        var audio = new Audio("/assets/sound/adminhelp.ogg");
        audio.muted = this.muted;
        audio.play();
      }
    },
  },
  mounted() {
    this.fetchInitialTickets();
    this.interval = setInterval(
      function () {
        this.pollForTickets();
      }.bind(this),
      10000
    );
  },
};
</script>

<style>
@keyframes added {
  from {
    background: #fff9c4;
  }

  to {
    background: transparent;
  }
}
.added {
  background: #fff9c4;
  animation-name: added;
  animation-duration: 2s;
  animation-fill-mode: forwards;
}
</style>