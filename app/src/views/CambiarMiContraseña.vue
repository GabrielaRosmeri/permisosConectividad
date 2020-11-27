<template>
  <v-content>
    <v-row align="center" justify="center" class="text-center pl-10 pr-10">
      <v-col cols="6" align="center" justify="center">
        <v-card>
          <v-card-title
            class="headline pa-2"
            style="
              border-left: 5px solid #1a237e !important;
              color: #1a237e !important;
            "
          >
            <v-icon style="color: #1a237e !important">mdi-layers</v-icon>
            <h6 class="pl-3">Cambiar mi contraseña</h6>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-col cols="12">
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
            </v-form>
            <v-row align="center">
              <v-col cols="12" align="end">
                <v-btn
                  color="indigo darken-4 white--text"
                  medium
                  small
                  @click="ConsultarContras()"
                  :disabled="disabled"
                >
                  <span>Aceptar</span>
                </v-btn>
              </v-col>
            </v-row>
            <v-divider v-show="mostrar()"></v-divider>
            <v-row align="center" class="pl-0" v-show="mostrar()">
              <v-col cols="12">
                <v-icon class="pt-3 pl-3" style="color: #1a237e !important"
                  >mdi-lock-reset</v-icon
                >
                <h4 class="pl-3 pt-3" style="color: #1a237e !important">
                  Nueva Contraseña
                </h4>
              </v-col>
              <v-col cols="12">
                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-row align="center" justify="center">
                    <v-col cols="6" align="center">
                      <v-text-field
                        :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show2 ? 'text' : 'password'"
                        v-model="nuevaClave"
                        :rules="[fieldRules.required, fieldRules.validarClave]"
                        label="Clave"
                        maxlength="10"
                        prepend-icon="mdi-lock"
                        @click:append="show2 = !show2"
                        required
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" align="center">
                      <v-text-field
                        :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show2 ? 'text' : 'password'"
                        v-model="confirmarNuevaClave"
                        :rules="[
                          fieldRules.required,
                          fieldRules.validarClave,
                          confirmarReestablecer,
                        ]"
                        label="Confirmar clave"
                        maxlength="10"
                        prepend-icon="mdi-lock"
                        @click:append="show2 = !show2"
                        required
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-form>
              </v-col>
            </v-row>
            <v-row align="center" v-show="mostrar()">
              <v-col cols="12" align="end">
                <v-btn
                  color="indigo darken-4"
                  text
                  medium
                  small
                  @click="limpiar()"
                  >Cancelar</v-btn
                >
                <v-btn
                  color="indigo darken-4"
                  class="ma-2 white--text"
                  depressed
                  medium
                  small
                  @mousedown="validate"
                  @click="reestabler()"
                  >Guardar</v-btn
                >
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { post, put } from "../api/api";
import Swal from "sweetalert2";
export default {
  data: () => ({
    show1: false,
    show2: false,
    valid: true,
    confirmar: true,
    mostrarRow: false,
    disabled: false,
    fieldRules: {
      required: (v) => !!v || "Campo requerido",
      validarClave: (v) => v.length <= 10 || "Clave incorrecta",
    },
    errors: [],
    ComprobarClave: "",
    nuevaClave: "",
    confirmarNuevaClave: "",
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
    limpiarError() {
      this.errors = [];
    },
    limpiar() {
      this.ComprobarClave = "";
      this.disabled = false;
      this.mostrarRow = false;
      this.confirmar = true;
      this.mostrar();
    },
    mostrar() {
      if (this.mostrarRow === true) return true;
      else return false;
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
    assembleUsuario() {
      return {
        usuario: this.usuarioIds,
        clave: this.ComprobarClave,
      };
    },
    ConsultarContras() {
      post("consultarC", this.assembleUsuario())
        .then(() => {
          this.mostrarRow = true;
          this.disabled = true;
        })
        .catch((e) => {
          if (e.message == 404) {
            this.errors = ["Contrase;a no valida"];
          }
        });
    },
    assembleContras() {
      return {
        clave: this.nuevaClave,
      };
    },
    reestabler() {
      if (this.valid == false) return;
      put("cambiarContras/" + this.usuarioIds, this.assembleContras()).then(
        () => {
          Swal.fire({
            title: "Sistema",
            text: "Contarseña actualizada correctamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
          this.limpiar();
        }
      );
    },
  },
  created() {
    this.usuarioIds = this.user.usuarioId;
  },
};
</script>

<style>
</style>