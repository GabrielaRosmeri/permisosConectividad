<template>
  <v-content>
    <v-row align="center" justify-center class="text-center pl-10 pr-10">
      <v-col cols="12" align="center" justify-center class="text-center">
        <v-card>
          <v-card-title
            class="headline pa-2"
            style="
              border-left: 5px solid #1a237e !important;
              color: #1a237e !important;
            "
          >
            <v-icon style="color: #1a237e !important">mdi-layers</v-icon>
            <h6 class="pl-3">Gestionar permisos por usuario</h6>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container class="pt-3">
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
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row class="pt-2 pl-12 pr-12" v-show="mostrar()">
      <v-col cols="6">
        <v-card>
          <v-card-title
            class="headline pa-2"
            style="
              border-left: 5px solid #1a237e !important;
              color: #1a237e !important;
            "
          >
            <v-icon style="color: #1a237e !important"
              >mdi-clipboard-check</v-icon
            >
            <h6 class="pl-3">Permisos asignados</h6>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container>
              <v-col cols="12" class="text-left">
                <v-treeview
                  v-model="active"
                  selectable
                  :items="opciones"
                  selected-color="indigo darken-4"
                  @input="cambiarEdit"
                >
                  <template v-slot:prepend="{ item }">
                    <v-icon color="#607D8B" dense="true">
                      {{ item.file }}
                    </v-icon>
                  </template>
                </v-treeview>
              </v-col>
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="6">
        <v-card>
          <v-card-title
            class="headline pa-2"
            style="
              border-left: 5px solid #1a237e !important;
              color: #1a237e !important;
            "
          >
            <v-icon style="color: #1a237e !important"
              >mdi-clipboard-alert</v-icon
            >
            <h6 class="pl-3">Permisos no asignados</h6>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container>
              <v-col cols="12" class="text-left">
                <v-treeview
                  v-model="noAsignados"
                  selectable
                  :items="opcionesNoAsignados"
                  selected-color="indigo darken-4"
                  @input="cambiarEdit"
                >
                  <template v-slot:prepend="{ item }">
                    <v-icon color="#607D8B" dense="true">
                      {{ item.file }}
                    </v-icon>
                  </template>
                </v-treeview>
              </v-col>
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row align="center" justify-center class="text-center" v-show="mostrar()">
      <v-divider></v-divider>
      <v-col cols="12">
        <v-btn
          color="indigo darken-4 white--text"
          elevation="5"
          :disabled="edit"
          @click="guardarOpcionesEmpleado"
        >
          <v-icon left dark>mdi-content-save</v-icon>
          Guardar
        </v-btn>
      </v-col>
    </v-row>
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { post } from "../api/api";
import Swal from "sweetalert2";
export default {
  data: () => ({
    selectionType: "leaf",
    local: null,
    locales: [],
    empleados: [],
    opciones: [],
    opcion: [],
    active: [],
    noAsignados: [],
    opcionesNoAsignados: [],
    empleado: null,
    valid: true,
    edit: true,
    mostrarRow: false,
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
    mostrar() {
      if (this.mostrarRow === true) return true;
      else return false;
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
        usuario: this.empleado,
      };
    },
    cargarOpciones() {
      post("listaOpcionesEmpleados", this.assembleOpcion()).then((data) => {
        console.log(data);
        this.mostrarRow = true;
        this.opciones = data.opcion;
        this.active = data.active;
        this.opcionesNoAsignados = data.noOption;
      });
    },
    cambiarEdit() {
      this.edit = false;
    },
    assembleRegistrar() {
      console.log(this.empleado);
      return {
        usuario: this.empleado,
        opcion: this.active,
        opcionNuevo: this.noAsignados,
      };
    },
    guardarOpcionesEmpleado() {
      this.edit = true;
      post("registrarOpcion", this.assembleRegistrar()).then(() => {
        this.cargarOpciones();
        Swal.fire({
          position: "top-end",
          title: "Sistema",
          text: "Opciones actualizados.",
          icon: "success",
          confirmButtonText: "OK",
          width: "400px",
          timer: 2500,
        });
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