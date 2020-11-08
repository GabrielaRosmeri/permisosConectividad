<template>
  <v-content>
    <v-row class="pa-2" align="center">
      <v-col cols="12">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Usuario
        </h2>
      </v-col>
    </v-row>
    <v-row align="center">
      <v-col cols="11" align="end">
        <v-btn color="indigo darken-4 white--text" elevation="5">
          <v-icon left dark>mdi-plus</v-icon>
          Usuario
        </v-btn>
      </v-col>
      <v-col class="pa-1" align="center">
        <v-col cols="10" align="center">
          <v-card>
            <v-card-title align="center">
              <v-row align="center" justify-center class="text-center pb-0">
                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-col class="d-flex" align="center" cols="12" sm="12">
                    <v-select
                      :items="items"
                      v-model="atributo"
                      label="Busqueda Personalizada"
                      dense
                      class="pa-4"
                      :rules="[fieldRules.required]"
                      @click="limpiarValidate"
                    ></v-select>
                    <v-text-field
                      v-model="busqueda"
                      label="Search"
                      single-line
                      hide-details
                      :rules="[fieldRules.required]"
                      @click="limpiarValidate"
                    ></v-text-field>
                    <v-btn
                      icon
                      color="indigo darken-4"
                      class="pt-8"
                      @mousedown="validate"
                      @click="busquedaUsuario"
                    >
                      <v-icon>mdi-magnify</v-icon>
                    </v-btn>
                  </v-col>
                </v-form>
              </v-row>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="usuarios"
              :loading="loading"
            >
              <template v-slot:top>
                <v-switch
                  v-model="todosU"
                  label="Todos los usuarios"
                  class="pt-0 pl-3"
                  @change="changeUsuario"
                ></v-switch>
              </template>
              <template v-slot:[`item.index`]="{ item }">
                {{ usuarios.indexOf(item) + 1 }}
              </template>
              <template v-slot:[`item.actions`]="{ item }">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                      v-bind="attrs"
                      v-on="on"
                      color="blue-grey"
                      class="mr-2"
                    >
                      {{
                        item.Vigencia
                          ? "mdi-close-circle-outline"
                          : "mdi-checkbox-marked-circle-outline"
                      }}
                    </v-icon>
                  </template>
                  <span>Editar</span>
                </v-tooltip>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
      </v-col>
    </v-row>
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { post } from "../api/api";
export default {
  data: () => ({
    todosU: false,
    valid: true,
    loading: false,
    fieldRules: {
      required: (v) => !!v || "Campo requerido",
    },
    headers: [
      {
        text: "N°",
        value: "index",
        width: "10%",
      },
      {
        text: "Nombre",
        align: "start",
        value: "Nombres",
        width: "20%",
      },
      { text: "Perfil", value: "Perfil", width: "30%" },
      { text: "Local", value: "Local", width: "30%" },
      { text: "Acciones", value: "actions", width: "10%" },
    ],
    items: [
      { text: "Nombre", value: "p.Nombres" },
      { text: "Perfil", value: "pf.Nombre" },
      { text: "Local", value: "l.Nombre" },
    ],
    busqueda: "",
    atributo: "",
    usuarios: [],
    estadoU: [1],
  }),
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiarValidate() {
      this.$refs.form.resetValidation();
    },
    changeUsuario() {
      if (this.todosU === true) {
        this.estadoU = [1, 0];
      } else {
        this.estadoU = [1];
      }
      this.busquedaUsuario();
    },
    assembleUser() {
      return {
        atributo: this.atributo,
        busqueda: this.busqueda,
        empresa: this.empresa,
        vigencia: this.estadoU,
      };
    },
    busquedaUsuario() {
      if (this.valid == false) return;
      this.loading = true;
      post("usuarios", this.assembleUser()).then((data) => {
        this.loading = false;
        this.usuarios = data;
      });
    },
  },
  created() {
    this.empresa = this.user.empresaId;
  },
};
</script>

<style>
</style>