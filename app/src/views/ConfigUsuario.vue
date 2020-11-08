<template>
  <v-content>
    <v-row align="center" justify-center class="text-center pl-10 pr-10">
      <v-col cols="12" align="center" justify-center class="text-center">
        <v-card>
          <v-card-title class="headline indigo lighten-4 pa-2">
            <h6 class="pl-3">Gestionar permisos usuarios</h6>
          </v-card-title>
          <v-card-text>
            <v-container class="pt-3">
              <v-subheader>Empleados</v-subheader>
              <v-row class="justify-center">
                <v-col cols="2">
                  <v-select
                    v-model="local"
                    :items="locales"
                    label="Seleccionar local"
                    persistent-hint
                  ></v-select>
                </v-col>
                <v-col cols="6">
                  <v-select
                    v-model="empleado"
                    :items="empleados"
                    label="Seleccionar empleados"
                    persistent-hint
                  ></v-select>
                </v-col>
              </v-row>
              <v-divider></v-divider>
              <v-subheader>Permisos por usuarios</v-subheader>
              <v-treeview selectable></v-treeview>
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { post } from "../api/api";
export default {
  data: () => ({
    local: null,
    locales: [],
    empleados: [],
    empleado: null,
  }),
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    assembleLocal() {
      return {
        empresa: this.empresa,
      };
    },
    listaLocales() {
      post("listaLocales", this.assembleLocal()).then((data) => {
        this.locales = data;
      });
    },
  },
  created() {
    this.empresa = this.user.empresaId;
    this.listaLocales();
  },
};
</script>

<style>
</style>