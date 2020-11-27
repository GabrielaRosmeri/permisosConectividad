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
              </v-col>
            </v-row>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container class="pt-3">
              <v-row align="center">
                <v-col cols="4" align="center">
                  <v-avatar color="indigo darken-1" size="70">
                    <span class="white--text headline">{{ split }}</span>
                  </v-avatar>
                  <h4 class="pl-3 pt-1">{{ cargo }}</h4>
                  <h4 class="pl-3 pt-3">{{ correoEmpresarial }}</h4>
                  <h4 class="pl-3 pt-3">{{ celularEmpresarial }}</h4>
                </v-col>
                <v-divider vertical></v-divider>
                <v-col cols="7">
                  <v-row class="d-flex" justify="center">
                    <v-col cols="6">
                      <v-text-field
                        v-model="nombre"
                        label="Nombre"
                        :disabled="disabled"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="apPaterno"
                        label="Apellido Paterno"
                        :disabled="disabled"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="apMaterno"
                        label="Apellido Materno"
                        :disabled="disabled"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="tipoD"
                        label="Tipo Documento"
                        disabled
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="numeroD"
                        label="Número Documento"
                        disabled
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
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="telefono"
                        label="Teléfono"
                        :disabled="disabled"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="celular"
                        label="Celular"
                        :disabled="disabled"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="6" class="d-flex">
                      <v-text-field
                        v-model="correo"
                        label="Correo"
                        :disabled="disabled"
                      ></v-text-field>
                    </v-col>
                  </v-row>
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
import { post } from "../api/api";
export default {
  data: () => ({
    usurioI: "",
    split: "",
    correoEmpresarial: "",
    celularEmpresarial: "",
    cargo: "",
    guardar: false,
    disabled: true,
  }),
  computed: {
    ...mapState(["user"]),
  },
  methods: {
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
        this.disabled = true;
        this.guardar = false;
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