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
      auth: false
    },
  },
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      auth: true
    },
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue"),
    meta: {
      auth: false
    },
  },
  {
    path: "/usuario",
    name: "Usuario",
    component: () => import("../views/Usuario.vue"),
    meta: {
      auth: true
    },
  },
  {
    path: "/configuracionPerfil",
    name: "ConfigPerfil",
    component: () => import("../views/ConfigPerfil.vue"),
    meta: {
      auth: true
    },
  },
  {
    path: "/configuracionUsuarios",
    name: "ConfigUsuario",
    component: () => import("../views/ConfigUsuario.vue"),
    meta: {
      auth: true,
      admin: true,
      personal: false
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
  let sw = true;
  if (to.meta.auth && !user) {
    next("/login");
  } else if(to.meta.auth && user){
    if (to.path == "/" ) {
      next();
      sw = false;
    } 

    user.modulos.forEach(modulo =>{
      modulo.opciones.forEach(opcion=>{
        if(opcion.url == to.path){
          next();
          sw = false
        } 

      })
    })
   if(sw) next('/');
  }else next()
});

export default router
