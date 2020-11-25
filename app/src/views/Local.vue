<template>
  <v-content>
    <v-row class="pa-2" align="center">
      <v-col cols="12">
        <h2 class="font-weight-regular text-center">Mantenimiento de Local</h2>
      </v-col>
    </v-row>
    <v-row align="center">
      <v-col cols="11" align="end">
        <v-btn
          color="indigo darken-4 white--text"
          elevation="5"
          @click="dialogEjemplo = true"
        >
          <v-icon left dark>mdi-plus</v-icon>
          Local
        </v-btn>
      </v-col>
      <v-col class="pa-1" align="center">
        <v-col cols="10" align="center">
          <v-card style="border-top: 5px solid #1a237e !important">
            <v-data-table
              background="blue darken-3"
              :headers="headers"
              :items="locales"
              :search="search"
              :item-class="itemFilaColor"
            >
              <template v-slot:[`item.index`]="{ item }">
                {{ locales.indexOf(item) + 1 }}
              </template>
              <template v-slot:[`item.actions`]="{ item }">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                      v-bind="attrs"
                      v-on="on"
                      class="mr-2"
                      color="blue-grey"
                      @click="showactualizar(item)"
                      >mdi-border-color</v-icon
                    >
                  </template>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                      v-bind="attrs"
                      v-on="on"
                      class="mr-2"
                      :color="item.Vigencia ? 'red lighten-1' : 'green'"
                      @click="deleteLocal(item)"
                      >{{
                        item.Vigencia
                          ? "mdi-close-circle-outline"
                          : "mdi-checkbox-marked-circle-outline"
                      }}</v-icon
                    >
                  </template>
                  <span>{{
                    item.Vigencia ? "Dar de baja" : "Dar de alta"
                  }}</span>
                </v-tooltip>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title
          class="headline"
          style="
            border-left: 5px solid #1a237e !important;
            color: #90a4ae !important;
            background: #e8eaf6 !important;
          "
        >
          <v-icon v-if="edit" style="color: #90a4ae !important"
            >mdi-account-settings</v-icon
          >
          <v-icon v-else style="color: #90a4ae !important"
            >mdi-account-plus</v-icon
          >
          <h6 v-if="edit" class="pl-3">Editar Local</h6>
          <h6 v-else class="pl-3">Nuevo Local</h6>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Nombre"
                  label="Nombre"
                  prepend-icon="mdi-store"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :disabled="ver"
                  v-model="Telefono"
                  label="Teléfono"
                  type="number"
                  maxlength="9"
                  prepend-icon="mdi-phone"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  :disabled="ver"
                  v-model="Direccion"
                  label="Dirección Local"
                  prepend-icon="mdi-map-marker"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { get, put, patch, post } from "../api/api";
import Swal from "sweetalert2";
export default {
  data() {
    return {
      edit: false,
      ver: false,
      alert: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      itemsEmpresa: [],
      headers: [
        {
          text: "Nº",
          value: "index",
          width: "10%",
          class: "light  darken-4 black--text",
        },
        {
          text: "Nombre",
          align: "start",
          // sortable: false,
          value: "Nombre",
          width: "25%",
          class: "light darken-4 black--text",
        },
        {
          text: "Dirección",
          value: "Direccion",
          width: "30%",
          class: "light  darken-4 black--text",
        },
        {
          text: "Teléfono",
          value: "Telefono",
          width: "20%",
          class: "light  darken-4 black--text",
        },
        {
          text: "Acciones",
          value: "actions",
          width: "15%",
          class: "light  darken-4 black--text",
        },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.showactualizar,
        },
        {
          name: "Eliminar",
          icon: "mdi-delete",
          function: this.deleteLocal,
        },
      ],
      locales: [],
      search: "",
      Codigo: "",
      Nombre: "",
      Direccion: "",
      Telefono: "",
    };
  },
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.codigo = "";
      this.Nombre = "";
      this.Direccion = "";
      this.Telefono = "";
      this.ver = false;
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerLocal();
      } else {
        this.actualizar();
      }
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
    assembleLocal() {
      return {
        Codigo: this.codigo,
        Nombre: this.Nombre,
        Direccion: this.Direccion,
        Telefono: this.Telefono,
        CodigoEmpresa: this.empresa,
      };
    },
    registerLocal() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("local", this.assembleLocal()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        //this.$refs.localTable.fetchData();
        this.limpiar();
        Swal.fire({
          title: "Sistema",
          text: "Local registrado exitosamente.",
          icon: "success",
          confirmButtonText: "Aceptar",
          timer: 2500,
        });
        this.actualizarLocales();
      });
    },
    actualizar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("locales/" + this.editId, this.assembleLocal())
        .then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.actualizarLocales();
          //this.$refs.localTable.fetchData();
          this.limpiar();
          Swal.fire({
            title: "Sistema",
            text: "Local actualizado exitosamente.",
            icon: "success",
            confirmButtonText: "Aceptar",
            timer: 2500,
          });
        })
        .catch(() => {
          this.alert = true;
        });
    },
    showactualizar(Local) {
      this.edit = true;
      this.editId = Local.Codigo;
      this.mostrarLocal(Local.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    deleteLocal(Local) {
      patch("locales/" + Local.Codigo)
        .then((data) => {
          console.log(data);
          if (data == false) {
            Swal.fire({
              title: "Sistema",
              text: "Local dado de alta exitosamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
              timer: 2500,
            });
          } else {
            Swal.fire({
              title: "Sistema",
              text: "Local dado de baja exitosamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
              timer: 2500,
            });
          }
          this.actualizarLocales();
        })
        //.catch(() => {
        //this.alert = true;
        //});
        .catch(() => {});
    },
    cambiarEstadoLocal(Local) {
      patch("locales/" + Local.Codigo)
        .then(() => {
          this.actualizarLocales();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    assembleEmpresa() {
      return {
        empresa: this.empresa,
      };
    },
    actualizarLocales() {
      post("locales", this.assembleEmpresa()).then((data) => {
        this.locales = data;
      });
    },
    async mostrarLocal(codigo) {
      const Local = await get("locales/" + codigo);
      this.codigo = Local.codigo;
      this.Nombre = Local.Nombre;
      this.Direccion = Local.Direccion;
      this.Telefono = Local.Telefono;
    },
  },
  created() {
    this.empresa = this.user.empresaId;
    this.actualizarLocales();
  },
};
</script>

<style>
</style>