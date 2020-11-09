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
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-row class="justify-center">
                  <v-col cols="2">
                    <v-select
                      v-model="local"
                      :items="locales"
                      label="Seleccionar local"
                      persistent-hint
                      :rules="[fieldRules.required]"
                      @change="busquedaEmpleado"
                    ></v-select>
                  </v-col>
                  <v-col cols="8">
                    <v-select
                      v-model="empleado"
                      :items="empleados"
                      label="Seleccionar empleados"
                      persistent-hint
                      :rules="[fieldRules.required]"
                    ></v-select>
                  </v-col>
                  <v-col cols="2">
                    <v-btn
                      icon
                      color="indigo darken-4"
                      class="pt-8"
                      @mousedown="validate"
                      @click="cargarOpciones"
                    >
                      <v-icon>mdi-magnify</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
              </v-form>
              <v-divider></v-divider>
              <v-subheader>Permisos por usuarios</v-subheader>
              <v-col cols="4" class="text-left">
                <v-treeview selectable :items="opciones" selected-color="indigo darken-4"></v-treeview>
              </v-col>
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
    opciones: [],
    empleado: null,
    valid: true,
    fieldRules: {
      required: (v) => !!v || "Campo requerido",
    },
  }),
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
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
    assembleEmpleado() {
      return {
        local: this.local,
      };
    },
    busquedaEmpleado() {
      post("listaEmpleados", this.assembleEmpleado()).then((data) => {
        this.empleados = data;
      });
    },
    assembleOpcion() {
      return {
        usuario: this.usuarioIds,
      };
    },
    cargarOpciones() {
      post("listaOpcionesEmpleados", this.assembleEmpleado()).then((data) => {
        this.opciones = data;
      });
    },
  },
  created() {
    this.empresa = this.user.empresaId;
    this.listaLocales();
    this.usuarioIds = this.user.usuarioId;
  },
};
</script>

<style>
</style>