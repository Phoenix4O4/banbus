<template>
  <section>
    <h3 class="border-b border-gray-300 pb-4 mb-4 text-2xl font-bold">
      Live Ticket Feed <i class="fas fa-circle text-red-500 animate-pulse"></i>
    </h3>
    <div class="flex itens-center mb-4">
      <label class="inline-flex items-center">
        <input type="checkbox" v-on:click="toggleNewTickets" />
        <span class="mx-2 whitespace-nowrap">Only Show New Tickets</span>
      </label>
      <button
        v-on:click="mute"
        class="bg-green-600 text-white block w-full p-2 text-xl font-bold hover:bg-green-400 rounded"
        target="_blank"
        rel="noopener noreferrer"
      >
        <i
          class="fa fa-fw"
          :class="[muted ? 'fa-volume-mute' : 'fa-volume-up']"
        ></i>
        {{ muted ? "Unmute Sound" : "Mute Sound" }}
      </button>
    </div>
    <p class="text-xs text-gray-300 text-center mb-4">{{ messages.text }}</p>
    <dl
      v-for="t in tickets"
      :key="t.id"
      :id="t.id"
      class="flex mb-4 pb-4 border-b border-gray-300 ticket added transition"
    >
      <dt
        class="whitespace-nowrap text-right pr-3 tabular-nums border-r border-gray-300 mr-3"
      >
        <a class="link" :href="'/tgdb/ticket/' + t.round + '/' + t.ticket"
          >#{{ t.round }}-{{ t.ticket }}</a
        >
        <span class="block text-gray-500 text-xs"
          >{{ t.timestamp }} <br />on {{ t.server.name }}
        </span>
      </dt>
      <dd>
        <span class="whitespace-nowrap border-b border-gray-30 pb-2">
          <i
            class="fa fa-fw pr-3"
            :class="['fa-' + t.icon, 'text-' + t.class]"
          ></i>
          <span> {{ t.action }} by </span>
          <userBadge v-if="t.sender" :user="t.sender" :key="t.id"></userBadge>
          <span v-if="t.recipient">to </span>
          <userBadge
            v-if="t.recipient"
            :user="t.recipient"
            :key="t.id"
          ></userBadge>
        </span>
        <p class="text-xl mt-2" v-html="t.message"></p>
      </dd>
    </dl>
  </section>
</template>

<script>
const initialTicketUrl = "?json=true";
const pollUrl = "/tgdb/ticket/live/poll/?json=true";
import userBadge from "./../common/userBadge.vue";

export default {
  components: {
    userBadge,
  },
  data() {
    return {
      tickets: [],
      lastId: 0,
      muted: true,
      messages: {
        type: "info",
        text: "Checking for new tickets...",
      },
      newTickets: false,
    };
  },
  methods: {
    mute() {
      this.muted = !this.muted;
    },
    toggleNewTickets() {
      this.newTickets = !this.newTickets;
      if (this.newTickets) {
        this.changeMessage("Only polling for newly opened tickets");
      } else {
        this.changeMessage("Polling for all ticket actions");
      }
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
      this.changeMessage("Checking for new tickets...");
      this.lastId = document.getElementsByClassName("ticket")[0].id;
      fetch(pollUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          lastId: this.lastId,
          newTickets: this.newTickets,
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if (0 < res.tickets.length) {
            this.bwoink();
          }
          this.tickets = [...res.tickets, ...this.tickets];
          this.changeMessage(res.messages[0].text);
        });
    },
    bwoink() {
      if (!this.muted) {
        var audio = new Audio("/assets/sound/adminhelp.ogg");
        audio.muted = this.muted;
        audio.play();
      }
    },
    changeMessage(m) {
      this.messages = {
        text: m,
      };
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