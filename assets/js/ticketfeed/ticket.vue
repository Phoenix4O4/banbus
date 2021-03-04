<template>
  <section>
    <h3
      class="border-b border-gray-300 dark:border-gray-700 pb-4 mb-4 text-2xl font-bold"
    >
      Live Ticket Feed <i class="fas fa-circle text-red-500 animate-pulse"></i>
    </h3>
    <div class="flex itens-center mb-4">
      <label class="inline-flex items-center">
        <input type="checkbox" v-on:click="toggleNewTickets" />
        <span class="mx-2 whitespace-nowrap">Only Show New Tickets</span>
      </label>
      <select
        v-on:change="changeServer"
        class="rounded-md bg-gray-200 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 mr-2"
      >
        <option value="all">All Servers</option>
        <option v-for="s in servers" v-bind:key="s.port" :value="s.port">
          {{ s.servername }}
        </option>
      </select>
      <button
        v-on:click="mute"
        class="bg-green-600 text-white block w-full p-2 text-xl font-bold hover:bg-green-400 rounded"
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
      class="flex mb-4 pb-4 border-b border-gray-300 dark:border-gray-700 ticket added transition"
    >
      <dt
        class="whitespace-nowrap text-right pr-3 tabular-nums border-r border-gray-300 dark:border-gray-700 mr-3"
      >
        <a
          class="link"
          :href="'/tgdb/ticket/' + t.round + '/' + t.ticket"
          target="_blank"
          rel="noopener noreferrer"
          >#{{ t.round }}-{{ t.ticket }}</a
        >
        <span class="block text-gray-500 text-xs"
          ><time>{{ t.timestamp || moment("from") }}</time> <br />on
          <gameLink :server="t.server"></gameLink>
        </span>
      </dt>
      <dd>
        <span
          class="whitespace-nowrap border-b border-gray-300 dark:border-gray-700 pb-2"
        >
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
const serverUrl = "/servers/";
import userBadge from "./../common/userBadge.vue";
import gameLink from "./../common/gameLink.vue";

export default {
  components: {
    userBadge,
    gameLink,
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
      servers: [],
      server: "all",
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
    fetchServerList() {
      fetch(serverUrl)
        .then((res) => res.json())
        .then((res) => {
          this.servers = res.servers;
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
          server: this.server, //TODO: #5 Do filtering clientside
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.tickets && 0 < res.tickets.length) {
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
    changeServer(event) {
      this.server = event.target.value;
      if ("all" === this.server) {
        this.changeMessage(`Polling for tickets on all servers`);
        return;
      }
      var server = this.servers.filter((obj) => {
        return obj.port == this.server;
      })[0];
      console.log(server);

      this.changeMessage(`Only polling for tickets from ${server.servername}`);
    },
  },
  //https://developers.google.com/web/updates/2012/01/Web-Audio-FAQ#q_i%E2%80%99ve_made_an_awesome_web_audio_api_application_but_whenever_the_tab_its_running_in_goes_in_the_background_sounds_go_all_weird
  // mounted() -> setInterval() {runs in the same context as setTimeout, async) -> pollForTickets() [async] -> bwoink() -> new Audio().play()
  mounted() {
    this.fetchInitialTickets();
    this.fetchServerList();
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