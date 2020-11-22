<template>
  <v-app style="background-color: #fafafa">
    <v-app-bar app color="indigo darken-4" dark v-if="$route.name != 'Login'">
      <v-app-bar-nav-icon
        @click="drawer = true"
        v-if="$route.name != 'Login'"
      ></v-app-bar-nav-icon>
      <div class="d-flex align-center"></div>
      <h2>{{ user ? user.empresa : "" }}</h2>
      <v-spacer></v-spacer>
    </v-app-bar>
    <v-navigation-drawer
      v-model="drawer"
      app
      temporary
      v-if="$route.name != 'Login'"
      class="mx-auto"
      width="300"
    >
      <v-system-bar color="indigo darken-4"></v-system-bar>
      <v-list v-if="$route.name != 'Login'">
        <v-list-item link>
          <v-list-item-content>
            <v-row justify-center>
              <v-col cols="12" sm="12">
                <v-list-item>
                  <v-list-item-avatar v-if="user">
                    <v-img :src="user.logo ? user.logo : getImgUrl()"></v-img>
                  </v-list-item-avatar>
                </v-list-item>
                <v-list-item-title class="title">
                  {{ user ? user.usuario : "" }}
                </v-list-item-title>
                <v-list-item-subtitle>{{
                  user ? user.correo : ""
                }}</v-list-item-subtitle>
              </v-col>
            </v-row>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-list v-for="(sistema, i) in user ? user.modulos : []" :key="i">
          <v-list-group :prepend-icon="sistema.icono" no-action>
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title
                  v-text="sistema.sistemaNombre"
                ></v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list v-for="(opciones, j) in sistema.opciones" :key="j">
              <v-list-item :to="opciones.url" link>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="opciones.opcion"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-list-group>
        </v-list>
      </v-list>
      <v-divider></v-divider>
      <v-container>
        <v-row>
          <v-col cols="12" sm="12">
            <v-btn text color="primary" @click="cerrarSession" block>
              <v-icon left>mdi-arrow-right-thick</v-icon>Salir
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-navigation-drawer>
    <v-main>
      <!-- <v-btn color="success" @click="cambiarTitulo" >Cambiar titulo</v-btn> -->
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
import { mapState } from "vuex";
import { removeUser } from "./api/api";
import store from "./store/index";
export default {
  name: "App",

  components: {},

  data: () => ({
    drawer: true,
    itemContent: [],
  }),
  methods: {
    cerrarSession() {
      removeUser();
      this.$router.push("/login");
    },
    getImgUrl() {
      return require("@/assets/user.svg");
    },
  },
  computed: {
    ...mapState(["user"]),
    ...mapState(["admin"]),
  },
  created() {
    store.commit("updateUser");
  },
};
</script>
