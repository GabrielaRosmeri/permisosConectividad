<template>
  <v-content>
    <v-container fill-height grid-list-lg>
      <v-layout align-center justify-center style="padding: 60px">
        <v-flex xs12>
          <v-form ref="form" v-model="valid" @submit.prevent="login">
            <v-layout row wrap>
              <v-flex xs12>
                <v-card
                  style="border-top: 5px solid #1a237e !important"
                  elevation="6"
                >
                  <v-card-text class="pl-0 pr-0 pt-0 pb-0">
                    <v-row>
                      <v-col cols="6">
                        <v-img
                          class="white--text"
                          height="500px"
                          :src="getImgUrl()"
                        >
                        </v-img>
                      </v-col>
                      <v-divider vertical></v-divider>
                      <v-col
                        class="text-center pl-10"
                        cols="5"
                        align-center
                        justify-center
                        style="padding-top:20vh !important"
                      >
                        <v-text-field
                          outlined
                          v-model="usuario"
                          append-icon="mdi-account"
                          label="Usuario"
                          placeholder="Nombre de usuario"
                          type="text"
                          :rules="[fieldRules.required]"
                          @click="limpiarValidate"
                        />
                        <v-text-field
                          outlined
                          v-model="clave"
                          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                          :type="show1 ? 'text' : 'password'"
                          label="Contraseña"
                          placeholder="Contraseña"
                          :rules="[fieldRules.required]"
                          @click="limpiarValidate"
                          @click:append="show1 = !show1"
                        />
                        <v-layout align-center justify-center>
                          <v-flex xs12 sm6 md4 xl4>
                            <v-btn
                              xs6
                              type="submit"
                              rounded
                              color="#292664"
                              class="white--text"
                              @mousedown="validate"
                            >
                              <span v-if="!ingresando">Ingresar</span>
                              <v-progress-circular
                                v-else
                                size="20"
                                width="2"
                                indeterminate
                                color="#FFF"
                              ></v-progress-circular>
                            </v-btn>
                          </v-flex>
                        </v-layout>
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-flex>
            </v-layout>
          </v-form>
        </v-flex>
      </v-layout>
    </v-container>
  </v-content>
</template>

<script>
import { post, setUser, getUser } from "../api/api";
import Swal from "sweetalert2";
export default {
  data: () => ({
    valid: true,
    enviando: false,
    ingresando: false,
    show1: false,
    usuario: "",
    clave: "",
    fieldRules: {
      required: (v) => !!v || "Campo requerido",
    },
  }),

  methods: {
    validate() {
      this.$refs.form.validate();
    },
    limpiarValidate() {
      this.$refs.form.resetValidation();
    },
    getImgUrl() {
      return require("@/assets/bienvenida.png");
    },
    login() {
      console.log(this.valid);
      if (this.valid == false) return;
      this.ingresando = true;
      post("login", {
        Nombre: this.usuario,
        Clave: this.clave,
      }).then((respuesta) => {
        if (respuesta.token) {
          Swal.fire({
            position: "top-end",
            title: "Sistema",
            text: "Bienvenido \n" + this.usuario,
            icon: "success",
            confirmButtonText: "OK",
            width: "400px",
            timer: 2500,
          });
          setUser({ ...respuesta, name: this.usuario });
          getUser();
          this.$router.push("/");
        } else {
          Swal.fire({
            title: "Sistema",
            text: "Credenciales Incorrectas",
            icon: "error",
            confirmButtonText: "OK",
            width: "400px",
          });
          this.ingresando = false;
        }
      });
    },
    limpiar() {
      this.usuario = "";
      this.clave = "";
      this.correo = "";
      this.enviando = false;
    },
  },
};
</script>
<style>
</style>