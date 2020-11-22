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
        <v-btn
          color="indigo darken-4 white--text"
          elevation="5"
          @click="abrirDialog()"
        >
          <v-icon left dark>mdi-plus</v-icon>
          Usuario
        </v-btn>
      </v-col>
      <v-col class="pa-1" align="center">
        <v-col cols="10" align="center">
          <v-card style="border-top: 5px solid #1a237e !important">
            <v-card-title align="center" justify-center class="text-center">
              <v-row
                align="center"
                justify-center
                class="text-center pb-0 pl-10"
              >
                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-row align="center" justify-center class="text-center">
                    <v-col
                      class="justify-content-center text-center"
                      align="center"
                      cols="6"
                    >
                      <v-select
                        :items="items"
                        v-model="atributo"
                        label="Busqueda Personalizada"
                        dense
                        class="pt-8"
                        :rules="[fieldRules.required]"
                        @click="limpiarValidate"
                      ></v-select>
                    </v-col>
                    <v-col
                      class="justify-content-center text-center"
                      align="center"
                      cols="5"
                    >
                      <v-text-field
                        v-model="busqueda"
                        label="Search"
                        single-line
                        hide-details
                        :rules="[fieldRules.required]"
                        @click="limpiarValidate"
                      ></v-text-field>
                    </v-col>
                    <v-col
                      class="justify-content-center text-center"
                      align="center"
                      cols="1"
                    >
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
                  </v-row>
                </v-form>
              </v-row>
            </v-card-title>

            <v-data-table
              :headers="headers"
              :items="usuarios"
              :loading="loading"
              :item-class="itemFilaColor"
            >
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
                      @click="showEditUsuario(item)"
                    >
                      mdi-border-color</v-icon
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
                      @click="deleteUsuario(item)"
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
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Usuario</span
          >
          <span v-else class="headline" style="color: white"
            >Nuevo Usuario</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="validD" lazy-validation>
            <v-row>
              <v-col :cols="edit ? '12' : '6'">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre"
                  prepend-icon="mdi-domain"
                  maxlength="30"
                  required
                ></v-text-field>
              </v-col>
              <v-col v-if="!edit" cols="6" class="pt-7">
                <v-select
                  :items="itemsPersonal"
                  v-model="CodigoPersonal"
                  label="Personal"
                  prepend-icon="mdi-account"
                  :rules="[fieldRules.required]"
                  dense
                ></v-select>
              </v-col>
            </v-row>
            <v-row>
              <v-col v-if="!edit" cols="6">
                <v-select
                  :items="itemsLocal"
                  v-model="CodigoLocal"
                  label="Local"
                  prepend-icon="mdi-home"
                  dense
                  :rules="[fieldRules.required]"
                ></v-select>
              </v-col>
              <v-col v-if="!edit" cols="6">
                <v-select
                  :items="optionsPerfil"
                  v-model="CodigoPerfil"
                  label="Perfil"
                  prepend-icon="mdi-tag"
                  dense
                  :rules="[fieldRules.required]"
                ></v-select>
              </v-col>
            </v-row>
            <v-row v-if="!edit">
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="Clave"
                  :rules="[fieldRules.required, fieldRules.validarClave]"
                  label="Clave"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @click:append="show1 = !show1"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ConfirmarClave"
                  :rules="[
                    fieldRules.required,
                    fieldRules.validarClave,
                    confirmarClave,
                  ]"
                  label="Confirmar clave"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @click:append="show1 = !show1"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row v-else>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ComprobarClave"
                  :rules="[fieldRules.validarClave]"
                  :error-messages="errors"
                  label="Clave actual"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @mouseup="limpiarError()"
                  @click:append="show1 = !show1"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ClaveNueva"
                  :rules="[fieldRules.validarClave]"
                  label="Clave nueva"
                  maxlength="10"
                  prepend-icon="mdi-domain"
                  @click:append="show1 = !show1"
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
import { post, get } from "../api/api";
export default {
  data: () => ({
    todosU: false,
    valid: true,
    loading: false,
    disabled: true,
    show1: false,
    show2: true,
    edit: false,
    alert: false,
    saveLoading: false,
    dialogEjemplo: false,
    optionsPerfil: [],
    itemsLocal: [],
    itemsPersonal: [],
    confirmar: true,
    comprobar: true,
    validD: true,
    fieldRules: {
      required: (v) => !!v || "Campo requerido",
      validarClave: (v) => v.length <= 10 || "Clave incorrecta",
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
      { text: "Acciones", value: "actions", width: "10%" },
    ],
    busqueda: "",
    atributo: "",
    usuarios: [],
    Nombre: "",
    CodigoPersonal: "",
    CodigoPerfil: "",
    Clave: "",
    CodigoLocal: "",
    ConfirmarClave: "",
    ComprobarClave: "",
    ClaveNueva: "",
  }),
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    validate() {
      this.$refs.form.validate();
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
    limpiarValidate() {
      this.$refs.form.resetValidation();
    },
    abrirDialog() {
      this.dialogEjemplo = true;
      this.mostrarPerfil();
      this.mostrarPersonal();
      this.mostrarLocal();
    },
    limpiar() {
      this.Nombre = "";
      this.CodigoPersonal = "";
      this.CodigoPerfil = "";
      this.Nombre = "";
      this.Clave = "";
      this.CodigoLocal = "";
      this.ConfirmarClave = "";
      this.edit = false;
      this.ClaveNueva = "";
      this.ComprobarClave = "";
      this.$refs.form.resetValidation();
    },
    confirmarClave(value) {
      if (value === this.Clave) {
        this.confirmar = true;
        return true;
      } else {
        this.confirmar = false;
        return "Clave no coincide.";
      }
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerUsuario();
      } else {
        this.editUsuario();
      }
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
        console.log(data.length);
        if (data.length === 0) {
          this.disabled = true;
        } else {
          this.disabled = false;
        }
        this.loading = false;
        this.usuarios = data;
      });
    },
    mostrarPerfil() {
      get("usuariosperfil").then((data) => {
        this.optionsPerfil = data;
      });
    },
    mostrarPersonal() {
      get("usuariospersonal").then((data) => {
        this.itemsPersonal = data;
      });
    },
    assembleLocal() {
      return {
        empresa: this.empresa,
      };
    },
    mostrarLocal() {
      post("usuarioslocal", this.assembleLocal()).then((data) => {
        this.itemsLocal = data;
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