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

import { defineAsyncComponent, defineComponent } from "vue";

const requireContext = require.context(
  "@/components", //path to components folder which are resolved automatically
  true,
  /\.vue$/i,
  "sync"
);
let componentNames = requireContext
  .keys()
  .map((file) => file.replace(/(^.\/)|(\.vue$)/g, ""));

let components = {};

componentNames.forEach((component) => {
  //component represents the component name
  components[component] = defineAsyncComponent(() =>
    //import each component dynamically
    import("@/components/components/" + component + ".vue")
  );
});
export default defineComponent({
  name: "App",
  data() {
    return {
      componentNames, // you need this if you want to loop through the component names in template
    };
  },
  components, //ES6 shorthand of components:components or components:{...components }
});