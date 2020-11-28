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
    path: "/local",
    name: "Local",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/cotizacion",
    name: "Cotizacion",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/factura",
    name: "Factura",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/devolucion",
    name: "Devolucion",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/listaPrecios",
    name: "Lista Precios",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/administracionArticulos",
    name: "Administracion de articulos",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/precios",
    name: "Precios",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/depositos",
    name: "Depositos",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/categoria",
    name: "Categorias",
    component: () => import("../views/Categoria.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/producto",
    name: "Producto",
    component: () => import("../views/Producto.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/pagosDiferidos",
    name: "Pagos Diferidos",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/creditos",
    name: "Creditos",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/ordenCompra",
    name: "Orden de Compra",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/devolucionCompra",
    name: "Devolucion de Compra",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
    },
  },
  {
    path: "/facturaCompra",
    name: "Factura de Compra",
    component: () => import("../views/Local.vue"),
    meta: {
      auth: true,
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
    },
  },
  {
    path: "/miPerfil",
    name: "Mi Perfil",
    component: () => import("../views/MiPerfil.vue"),
    meta: {
      auth: true,
      admin: true,
      personal: false
    },
  },
  {
    path: "/cambiarContrase",
    name: "Cambiar Mi Contraseña",
    component: () => import("../views/CambiarMiContraseña.vue"),
    meta: {
      auth: true,
      admin: true,
      personal: false
    },

  },
  {
    path: "/personal",
    name: "Personal",
    component: () => import("../views/Personal.vue"),
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
  } else if (to.meta.auth && user) {
    if (to.path == "/") {
      next();
      sw = false;
    }

    user.modulos.forEach(modulo => {
      modulo.opciones.forEach(opcion => {
        if (opcion.url == to.path) {
          next();
          sw = false
        }

      })
    })
    if (sw) next('/');
  } else next()
});

export default router
