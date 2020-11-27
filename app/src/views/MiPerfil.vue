<template>
  <v-content>
    <v-row align="center" justify-center class="text-center pl-10 pr-10">
      <v-col cols="12" align="center">
        <v-card>
          <v-card-title
            class="headline pa-2"
            style="
              border-left: 5px solid #1a237e !important;
              color: #1a237e !important;
            "
          >
            <v-icon style="color: #1a237e !important">mdi-layers</v-icon>
            <h6 class="pl-3">Datos Generales</h6>
            <v-row align="center">
              <v-col cols="12" align="end">
                <v-btn
                  color="indigo darken-4 white--text"
                  medium
                  small
                  @click="eventoEdit()"
                >
                  <span v-if="!guardar">Editar</span>
                  <span v-else>Guardar</span>
                </v-btn>
                <v-btn
                  v-if="guardar"
                  color="indigo darken-4"
                  text
                  medium
                  small
                  @click="limpiar()"
                  >Cancelar</v-btn
                >
              </v-col>
            </v-row>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container class="pt-3">
              <v-row align="center">
                <v-col cols="4" align="center">
                  <v-avatar color="lime" size="70">
                    <span class="white--text headline">{{ split }}</span>
                  </v-avatar>
                  <h4 class="pl-3 pt-1">
                    {{ cargo }}
                  </h4>
                  <h4 class="pl-3 pt-3" style="color: #1a237e !important">
                    {{ correoEmpresarial }}
                  </h4>
                  <h4 class="pl-3 pt-3" style="color: #1a237e !important">
                    {{ celularEmpresarial }}
                  </h4>
                </v-col>
                <v-col
                  cols="8"
                  style="border-left: 5px solid #1a237e !important"
                >
                  <v-form ref="form" v-model="valid" lazy-validation>
                    <v-row class="d-flex" justify="center">
                      <v-col cols="6">
                        <v-text-field
                          v-model="nombre"
                          label="Nombre"
                          :disabled="disabled"
                          :rules="[fieldRules.required]"
                          prepend-icon="mdi-account"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="apPaterno"
                          label="Apellido Paterno"
                          :disabled="disabled"
                          :rules="[fieldRules.required]"
                          prepend-icon="mdi-account"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="apMaterno"
                          label="Apellido Materno"
                          :disabled="disabled"
                          :rules="[fieldRules.required]"
                          prepend-icon="mdi-account"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="tipoD"
                          label="Tipo Documento"
                          disabled
                          prepend-icon="mdi-account-card-details"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="numeroD"
                          label="Número Documento"
                          disabled
                          prepend-icon="mdi-account-card-details"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="sistemaP"
                          label="Sistema Pensiones"
                          disabled
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="direccion"
                          label="Dirección"
                          :disabled="disabled"
                          :rules="[fieldRules.required]"
                          prepend-icon="mdi-map-marker-outline"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="telefono"
                          label="Teléfono"
                          :disabled="disabled"
                          :rules="[fieldRules.required]"
                          prepend-icon="mdi-phone"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="celular"
                          label="Celular"
                          :disabled="disabled"
                          prepend-icon="mdi-cellphone"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="6" class="d-flex">
                        <v-text-field
                          v-model="correo"
                          label="Correo"
                          :disabled="disabled"
                          :rules="[fieldRules.required, fieldRules.email]"
                          prepend-icon="mdi-email"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-form>
                </v-col>
              </v-row>
            </v-container>
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
    usurioI: "",
    split: "",
    correoEmpresarial: "",
    celularEmpresarial: "",
    cargo: "",
    nombre: "",
    apPaterno: "",
    apMaterno: "",
    numeroD: "",
    direccion: "",
    telefono: "",
    correo: "",
    celular: "",
    tipoD: "",
    sistemaP: "",
    guardar: false,
    disabled: true,
    valid: true,
    fieldRules: {
      required: (v) => !!v || "Campo requerido",
      email: (v) => {
        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(v) || "Correo electrónico incorrecto.";
      },
    },
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
    limpiar() {
      this.disabled = true;
      this.guardar = false;
      this.limpiarValidate();
      this.datosUsuario();
    },
    assembleUsuarioLocal() {
      return {
        usuario: this.idUsuario,
        local: this.idLocal,
      };
    },
    eventoEdit() {
      if (this.guardar === false) {
        this.disabled = false;
        this.guardar = true;
      } else {
        this.editDatos();
      }
    },
    datosUsuario() {
      post("usuarioDatos", this.assembleUsuarioLocal()).then((data) => {
        this.correoEmpresarial = data.CorreoEmpresarial;
        this.cargo = data.Cargo;
        this.celularEmpresarial = data.CelularEmpresarial;
        this.nombre = data.Nombres;
        this.apPaterno = data.ApellidoPaterno;
        this.apMaterno = data.ApellidoMaterno;
        this.numeroD = data.NumeroDocumento;
        this.direccion = data.Direccion;
        this.telefono = data.Telefono;
        this.correo = data.Correo;
        this.celular = data.Celular;
        this.tipoD = data.Documento;
        this.sistemaP = data.Siglas;
      });
    },
    assembleEdit() {
      return {
        Nombres: this.nombre,
        ApellidoPaterno: this.apPaterno,
        ApellidoMaterno: this.apMaterno,
        Direccion: this.direccion,
        Telefono: this.telefono,
        Celular: this.celular,
        Correo: this.correo,
      };
    },
    editDatos() {
      if (this.valid == false) return;
      put("actualizarDatos/" + this.idUsuario, this.assembleEdit()).then(() => {
        Swal.fire({
          title: "Sistema",
          text: "Datos personales actualizados correctamente.",
          icon: "success",
          confirmButtonText: "OK",
          timer: 2500,
        });
        this.limpiar();
      });
    },
  },
  created() {
    this.usurioI = this.user.usuario;
    this.idUsuario = this.user.usuarioId;
    this.idLocal = this.user.local;
    this.split = this.usurioI.split(" ")[0][0] + this.usurioI.split(" ")[1][0];
    this.datosUsuario();
  },
};
</script>

<style>
</style>