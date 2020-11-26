<template>
  <v-content>
    <v-row class="pa-2" align="center">
      <v-col cols="12">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Usuario
        </h2>
      </v-col>
    </v-row>
    <!-- INICIO DE VISTA -->
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
                      color="teal accent-4"
                      class="mr-2"
                      @click="showReestablecer(item)"
                    >
                      mdi-lock-reset</v-icon
                    >
                  </template>
                  <span>Reestablecer contraseña</span>
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
    <!-- FINALIZACION -->
    <!-- DIALOG DE REGISTAR Y EDITAR -->
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
          <h6 v-if="edit" class="pl-3">Editar Usuario</h6>
          <h6 v-else class="pl-3">Nuevo Usuario</h6>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form ref="form" v-model="validD" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre"
                  prepend-icon="mdi-account-card-details"
                  maxlength="30"
                  :error-messages="errorsU"
                  @mouseup="limpiarError()"
                  required
                ></v-text-field>
              </v-col>
              <v-col v-if="!edit" cols="6" class="pt-7">
                <v-select
                  :items="itemsLocal"
                  v-model="CodigoLocal"
                  label="Local"
                  prepend-icon="mdi-home"
                  dense
                  :rules="[fieldRules.required]"
                  @change="mostrarPersonal"
                ></v-select>
              </v-col>
              <v-col v-if="edit" cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ComprobarClave"
                  :rules="[fieldRules.validarClave]"
                  :error-messages="errors"
                  label="Clave actual"
                  placeholder="Ingresar clave actual"
                  maxlength="10"
                  prepend-icon="mdi-lock"
                  @mouseup="limpiarError()"
                  @click:append="show1 = !show1"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col v-if="!edit" cols="6">
                <v-autocomplete
                  :items="itemsPersonal"
                  v-model="CodigoPersonal"
                  label="Personal"
                  prepend-icon="mdi-account"
                  :rules="[fieldRules.required]"
                  dense
                  clearable
                ></v-autocomplete>
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
                  prepend-icon="mdi-lock"
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
                  prepend-icon="mdi-lock"
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
                  v-model="ClaveNueva"
                  :rules="[fieldRules.validarClave]"
                  label="Clave nueva"
                  placeholder="Ingresar nueva clave"
                  maxlength="10"
                  prepend-icon="mdi-lock"
                  @click:append="show1 = !show1"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="ConfirmarClaveEditar"
                  :rules="[
                    fieldRules.required,
                    fieldRules.validarClave,
                    confirmarClaveEdit,
                  ]"
                  label="Confirmar clave"
                  maxlength="10"
                  prepend-icon="mdi-lock"
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
    <!-- FINALIZACION -->
    <!-- DIALOG DE REESTABLECER -->
    <v-dialog
      v-model="dialogReestablecer"
      persistent
      scrollable
      max-width="60vw"
    >
      <v-card>
        <v-card-title
          class="headline"
          style="
            border-left: 5px solid #1a237e !important;
            color: #90a4ae !important;
            background: #e8eaf6 !important;
          "
        >
          <v-icon style="color: #90a4ae !important">mdi-lock-reset</v-icon>
          <h6 class="pl-3">Reestablecer Contraseña</h6>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-form ref="form" v-model="validR" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="nuevaClave"
                  :rules="[fieldRules.required, fieldRules.validarClave]"
                  label="Clave"
                  maxlength="10"
                  prepend-icon="mdi-lock"
                  @click:append="show1 = !show1"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show1 ? 'text' : 'password'"
                  v-model="confirmarNuevaClave"
                  :rules="[
                    fieldRules.required,
                    fieldRules.validarClave,
                    confirmarReestablecer,
                  ]"
                  label="Confirmar clave"
                  maxlength="10"
                  prepend-icon="mdi-lock"
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
            @click="(dialogReestablecer = false), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="reestabler"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- FINALIZACION -->
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { post, get, put, patch } from "../api/api";
import Swal from "sweetalert2";
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
    dialogReestablecer: false,
    optionsPerfil: [],
    itemsLocal: [],
    itemsPersonal: [],
    confirmar: true,
    comprobar: true,
    validD: true,
    validR: true,
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
      { text: "Usuario", value: "Usuario", width: "20%" },
      { text: "Perfil", value: "Perfil", width: "20%" },
      { text: "Local", value: "Local", width: "15%" },
      { text: "Acciones", value: "actions", width: "15%" },
    ],
    items: [
      { text: "Nombre", value: "p.Nombres" },
      { text: "Perfil", value: "pf.Nombre" },
      { text: "Local", value: "l.Nombre" },
      { text: "Usuario", value: "u.Nombre" },
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
    nuevaClave: "",
    confirmarNuevaClave: "",
    errors: [],
    errorsU: [],
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
      this.nuevaClave = "";
      this.confirmarNuevaClave = "";
      this.$refs.form.resetValidation();
    },
    limpiarError() {
      this.errors = [];
      this.errorsU = [];
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
    confirmarClaveEdit(value) {
      if (value === this.ClaveNueva) {
        this.confirmar = true;
        return true;
      } else {
        this.confirmar = false;
        return "Clave no coincide.";
      }
    },
    confirmarReestablecer(value) {
      if (value === this.nuevaClave) {
        this.confirmar = true;
        return true;
      } else {
        this.confirmar = false;
        return "Clave no coincide.";
      }
    },
    comprobarClave() {
      if (this.comprobar === false) {
        return "Clave no coincide.";
      } else {
        return true;
      }
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerUsuario();
      } else {
        this.editUsuario();
      }
    },
    assembleUser() {
      return {
        atributo: this.atributo,
        busqueda: this.busqueda,
        empresa: this.empresa,
      };
    },
    busquedaUsuario() {
      if (this.valid == false) return;
      this.loading = true;
      post("usuariosLista", this.assembleUser()).then((data) => {
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
    assembleEmpleado() {
      return {
        local: this.CodigoLocal,
      };
    },
    mostrarPersonal() {
      post("usuariospersonal", this.assembleEmpleado()).then((data) => {
        this.itemsPersonal = data;
      });
    },
    showEditUsuario(usuario) {
      this.edit = true;
      this.editId = usuario.Codigo;
      this.limpiarError();
      this.mostrarUsuario(usuario.Codigo).then(() => {
        this.dialogEjemplo = true;
        this.mostrarPerfil();
        this.mostrarPersonal();
        this.mostrarLocal();
      });
    },
    showReestablecer(usuario) {
      this.editId = usuario.Codigo;
      this.dialogReestablecer = true;
    },
    assembleRegistrar() {
      return {
        CodigoPersonal: this.CodigoPersonal,
        CodigoPerfil: this.CodigoPerfil,
        Nombre: this.Nombre,
        Clave: this.Clave,
        CodigoLocal: this.CodigoLocal,
        Tipo: "Personal",
      };
    },
    registerUsuario() {
      if (this.validD == false) return;
      if (this.confirmar == false) return;
      this.saveLoading = true;
      post("usuarios", this.assembleRegistrar())
        .then(() => {
          this.saveLoading = false;
          this.dialogEjemplo = false;
          this.limpiar();
          Swal.fire({
            title: "Sistema",
            text: "Usuario registrado correctamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
        })
        .catch((e) => {
          if (e.message == 404) {
            this.errorsU = ["Usuario ya existe"];
          }
          this.saveLoading = false;
        });
    },
    assembleEdit() {
      return {
        Nombre: this.Nombre,
        ComprobarClave: this.ComprobarClave,
        Clave: this.ClaveNueva, // y la antigua? ay
      };
    },
    editUsuario() {
      if (this.validD == false) return;
      this.saveLoading = true;
      put("usuarios/" + this.editId, this.assembleEdit())
        .then(() => {
          this.limpiar();
          this.comprobar = true;
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.actualizarUsuarios();
          this.busquedaUsuario();
          Swal.fire({
            title: "Sistema",
            text: "Usuario actualizado correctamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
          this.$refs.usuarioTable.fetchData();
        })
        .catch((e) => {
          if (e.message == 404) {
            this.errorsU = ["Usuario ya existe"];
          }
          if (e.message == 501) {
            this.errors = ["Contrase;a no valida"];
          }
          this.saveLoading = false;
        });
    },
    deleteUsuario(usuario) {
      patch("usuario/" + usuario.Codigo)
        .then((data) => {
          if (data.Vigencia == false) {
            Swal.fire({
              title: "Sistema",
              text: "Usuario dado de baja correctamente.",
              icon: "success",
              confirmButtonText: "OK",
              timer: 2500,
            });
          } else {
            Swal.fire({
              title: "Sistema",
              text: "Usuario dado de alta correctamente.",
              icon: "success",
              confirmButtonText: "OK",
              timer: 2500,
            });
          }
          this.actualizarUsuarios();
          this.busquedaUsuario();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    assembleReestablecer() {
      return {
        clave: this.nuevaClave, // y la antigua? ay
      };
    },
    reestabler() {
      console.log(this.validR, this.confirmar);
      if (this.validR == false) return;
      if (this.confirmar == false) return;
      this.saveLoading = true;
      put("reestablecer/" + this.editId, this.assembleReestablecer()).then(
        () => {
          this.limpiar();
          this.saveLoading = false;
          this.dialogReestablecer = false;
          this.editId = null;
          Swal.fire({
            title: "Sistema",
            text: "Contraseña reestablecida correctamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
        }
      );
    },
    actualizarUsuarios() {
      get("usuarios").then((data) => {
        this.usuarios = data;
      });
    },
    async mostrarUsuario(codigo) {
      const usuario = await get("usuarios/" + codigo);
      this.CodigoPersonal = usuario.CodigoPersonal;
      this.CodigoPerfil = usuario.CodigoPerfil;
      this.Nombre = usuario.Nombre;
      this.Clave = usuario.Clave;
      this.CodigoLocal = usuario.CodigoLocal;
    },
  },
  created() {
    this.empresa = this.user.empresaId;
  },
};
</script>

<style>
</style>