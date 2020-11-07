import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../views/Home.vue';
import { getUser } from "../api/api";

Vue.use(VueRouter)

const routes = [
  {
    path: "*",
    redirect: "/",
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue"),
    meta: {
      auth: false,
      admin: false,
      personal: false
    },
  },
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      auth: true,
      admin: true,
      personal: true
    },
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  let user = getUser();
  if (to.meta.auth && !user) {
    next("/login");
  } else next();
});

export default router
