<template>
  <v-app>
    <v-app-bar app color="indigo darken-4" dark>
      <v-app-bar-nav-icon @click="drawer = true" v-if="$route.name != 'Login'"></v-app-bar-nav-icon>
      <div class="d-flex align-center"></div>
      <h2>Bienvenido</h2>
      <v-spacer></v-spacer>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" app temporary>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">Aplicacion</v-list-item-title>
          <v-list-item-subtitle></v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-navigation-drawer>
    <v-main>
      <!-- <v-btn color="success" @click="cambiarTitulo" >Cambiar titulo</v-btn> -->
      <router-view />
    </v-main>
  </v-app>
</template>

<script>

export default {
  name: "App",

  components: {},

  data: () => ({
    drawer: true,
  }),
  methods: {},
};
</script>
