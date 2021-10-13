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
      <label class="inline-flex items-center">
        <input type="checkbox" v-on:click="toggleNoAdmins" />
        <span class="mx-2 whitespace-nowrap"
          >Only Show Actions From Servers Without Any Admins</span
        >
      </label>
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
    <div class="grid grid-cols-3 gap-2 mx-auto">
      <span
        v-for="(s, server) in servers"
        v-bind:key="s.serverdata.port"
        @click="toggleServer(server)"
        class="alert flex justify-between items-center"
        :class="{
          'alert-success': '0' === s.gamestate,
          'alert-success': 1 === s.gamestate,
          'alert-info': 2 === s.gamestate,
          'alert-info': 3 === s.gamestate,
          'alert-danger': 4 === s.gamestate,
          'alert-danger': s.error,
          'opacity-50': !s.toggled,
          'delta-mode alert-danger': 'delta' == s.security_level,
        }"
        ><span
          >{{ s.serverdata.servername }}
          <span v-if="!s.error"
            >(<i class="fas fa-user" title="Players"></i> {{ s.players }},
            <i class="fas fa-user-shield" title="Admins"></i>
            {{ s.admins }})
            {{ moment.utc(s.round_duration * 1000).format("HH:mm:ss") }}</span
          ><br />
          <span v-if="'idle' != s.shuttle_mode && s.shuttle_mode"
            ><i class="fas fa-rocket" title="Shuttle Status"></i>
            {{ moment.utc(s.shuttle_timer * 1000).format("HH:mm:ss") }} -
            <span class="capitalize">{{ s.shuttle_mode }}</span></span
          >
        </span>
        <span>
          <i
            v-if="s.hub"
            class="fas fa-globe pr-2"
            tite="Server is On The Hub"
          ></i>
          <span v-if="s.security_level">
            <i
              class="fas fa-circle"
              tite="Security Level"
              :class="{
                'text-danger': 'red' === s.security_level,
                'text-info': 'blue' === s.security_level,
                'text-success': 'green' === s.security_level,
                'text-yellow-400 animate-ping': 'delta' === s.security_level,
              }"
            ></i>
          </span>
        </span>
      </span>
    </div>
    <p class="text-xs text-gray-300 text-center mb-4">{{ messages.text }}</p>
    <dl
      v-for="t in tickets"
      :key="t.id"
      :id="t.id"
      :class="{ hidden: t.hide }"
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
          ><time>{{ moment.utc(t.timestamp.date).fromNow() }}</time> <br />on
          <gameLink :server="t.server"></gameLink>
        </span>
      </dt>
      <dd>
        <span
          class="whitespace-nowrap border-b border-gray-300 dark:border-gray-700 pb-2"
        >
          <i
            class="fa pr-1"
            :class="{
              'fa-ticket-alt text-blue-400': 'Ticket Opened' === t.action,
              'fa-reply text-yellow-400': 'Reply' === t.action,
              'fa-check-circle text-green-400': 'Resolved' === t.action,
              'fa-undo text-red-400': 'Rejected' === t.action,
              'fa-times-circle text-red-400': 'Closed' === t.action,
              'fa-gavel text-purple-400': 'IC Issue' === t.action,
              'fa-window-close text-gray-400': 'Disconnected' === t.action,
              'fa-network-wired text-purple-400': 'Reconnected' === t.action,
            }"
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
const serverUrl = "https://tgstation13.org/dynamicimages/serverinfo.json";
import userBadge from "./../common/userBadge.vue";
import gameLink from "./../common/gameLink.vue";
import moment from "moment";

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
      noAdmins: false,
      servers: [],
      toggledServers: [],
      canBwoink: false,
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
    toggleNoAdmins() {
      this.noAdmins = !this.noAdmins;
      if (this.noAdmins) {
        this.changeMessage(
          "Only polling for actions from servers without any admins"
        );
        for (const [key, value] of Object.entries(this.servers)) {
          if (value.admins > 0 && value.admins) {
            this.toggleServer(key);
          }
        }
      } else {
        this.unToggleServers();
        this.changeMessage("Polling for actions from all servers");
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
          delete res.refreshtime;
          for (const [key, value] of Object.entries(res)) {
            res[key].toggled = true;
            if (this.noAdmins) {
              if (value.admins > 0 && value.admins) {
                res[key].toggled = false;
              } else {
                res[key].toggled = true;
              }
            }
            if (this.toggledServers[key]) {
              res[key].toggled = false;
            }
          }
          this.servers = res;
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
      var value = event.target.value;
      if ("all" === value) {
        this.changeMessage(`Polling for tickets on all servers`);
        this.server.serverdata.port = null;
        return;
      }
      console.log(value);
      this.server = this.servers[value];
      this.changeMessage(
        `Only polling for tickets from ${this.server.serverdata.servername}`
      );
    },
    toggleServer(server) {
      this.servers[server].toggled = !this.servers[server].toggled;
      this.toggledServers[server] = !this.toggledServers[server];
      if (true === this.toggledServers[server]) {
        this.changeMessage(`Hiding new actions from ${server}`);
      } else {
        this.changeMessage(`Showing new actions from ${server}`);
      }
    },
    unToggleServers() {
      this.toggledServers = [];
      for (const [key, value] of Object.entries(this.servers)) {
        this.servers[key].toggled = true;
      }
    },
    roundDuration(duration) {
      return duration;
    },
  },
  //https://developers.google.com/web/updates/2012/01/Web-Audio-FAQ#q_i%E2%80%99ve_made_an_awesome_web_audio_api_application_but_whenever_the_tab_its_running_in_goes_in_the_background_sounds_go_all_weird
  // mounted() -> setInterval() {runs in the same context as setTimeout, async) -> pollForTickets() [async] -> bwoink() -> new Audio().play()
  mounted() {
    this.fetchInitialTickets();
    this.fetchServerList();
    this.interval = setInterval(
      function () {
        this.fetchServerList();
        this.pollForTickets();
      }.bind(this),
      10000
    );
  },
  created: function () {
    this.moment = moment;
  },
};
</script>
