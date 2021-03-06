import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";


const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/round/:round/stats",
      props: true,
      components: {
        sidebar: () => import("./statlist.vue"),
      },
    },
    {
      path: "/round/:round/stat/:stat",
      props: true,
      components: {
        sidebar: () => import("./statlist.vue"),
        statview: () => import("./stat.vue"),
      },
    },
  ],
});

const app = createApp({
});
app.use(router);


window.vm = app.mount("#main");