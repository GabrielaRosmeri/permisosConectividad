<template>
  <v-container style="padding: 0px">
    <v-row class="pa-5 align-center">
      <!-- <v-col>
        <v-btn fab large dark color="blue " @click="dialogEjemplo = true">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col> -->
      <v-col cols="11">
        <h2 class="font-weight-bold text-center">Mantenimiento de Personal</h2>
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
          <h6 v-if="edit" class="pl-3">Editar Personal</h6>
          <h6 v-else class="pl-3">Nuevo Personal</h6>
        </v-card-title>

        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <div align="center">
              <br />
              <h2>DATOS PERSONALES</h2>
            </div>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombres"
                  :rules="[fieldRules.required]"
                  label="Nombres*"
                  type="text"
                  prepend-icon="mdi-border-color"
                >
                </v-text-field>
              </v-col>

              <v-col cols="6">
                <v-text-field
                  v-model="ApellidoPaterno"
                  :rules="[fieldRules.required]"
                  label="Apellido Paterno*"
                  prepend-icon="mdi-border-color"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="ApellidoMaterno"
                  :rules="[fieldRules.required]"
                  label="Apellido Materno*"
                  type="text"
                  prepend-icon="mdi-border-color"
                >
                </v-text-field>
              </v-col>
              <v-col cols="6">
                <v-combobox
                  v-model="Siglas"
                  :items="auxListaSNP"
                  label="Sistema Nacional de Pensiones*"
                  prepend-icon="mdi-domain"
                ></v-combobox>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-combobox
                  v-model="Documento"
                  :items="auxListaDocumento"
                  :rules="[fieldRules.required, fieldRules.validarDP]"
                  type="text"
                  label="Tipo de Documento Personal*"
                  prepend-icon="mdi-account-box"
                >
                </v-combobox>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="NumeroDocumento"
                  :rules="[fieldRules.validarCantidadNro]"
                  label="N° Documento*"
                  type="number"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Direccion"
                  :rules="[fieldRules.required]"
                  label="Dirección*"
                  prepend-icon="mdi-domain"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Telefono"
                  label="Teléfono"
                  type="text"
                  maxlength="10"
                  prepend-icon="mdi-phone"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Correo"
                  :rules="[fieldRules.required, fieldRules.email]"
                  label="Correo*"
                  prepend-icon="mdi-email"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Celular"
                  :rules="[fieldRules.required, fieldRules.numCel]"
                  label="Celular*"
                  type="text"
                  maxlength="9"
                  prepend-icon="mdi-cellphone-iphone"
                ></v-text-field>
              </v-col>
            </v-row>
            <div align="center">
              <br />
              <h2>DATOS LABORALES</h2>
            </div>
            <v-row>
              <v-col cols="6">
                <v-combobox
                  v-model="Local"
                  :items="localNombre"
                  :rules="[fieldRules.required, fieldRules.vallocal]"
                  type="text"
                  label="Local*"
                  prepend-icon="mdi-home"
                >
                </v-combobox>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Cargo"
                  :rules="[fieldRules.required]"
                  label="Cargo*"
                  type="text"
                  maxlength="9"
                  prepend-icon="mdi-account"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Sueldo"
                  :rules="[fieldRules.required]"
                  label="Sueldo*"
                  prepend-icon="mdi-currency-usd"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="CorreoEmpresarial"
                  :rules="[fieldRules.required, fieldRules.email]"
                  label="Correo empresarial*"
                  type="text"
                  prepend-icon="mdi-email"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="CelularEmpresarial"
                  :rules="[fieldRules.required, fieldRules.numCel]"
                  label="Celular empresarial*"
                  type="text"
                  maxlength="9"
                  prepend-icon="mdi-cellphone-iphone"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
          <span class="red--text">(*) Campos Obligatorios</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="indigo darken-4"
            text
            @click="((dialogEjemplo = false), (edit = false)), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            color="indigo darken-4"
            class="ma-2 white--text"
            @click="executeEventClick"
            >Cuardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogEjemplo02" persistent max-width="40vw">
      <v-card>
        <v-alert
          v-model="alert02"
          type="error"
          icon="mdi-cloud-alert"
          close-text="Close Alert"
        >
          Ocurrio un error al Registrar, Verificar los campos ingresados
        </v-alert>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="indigo darken-4"
            class="ma-2 white--text"
            @click="dialogEjemplo02 = false"
            >Cancelar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogEditar" persistent scrollable max-width="60vw">
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
          <h6 v-if="edit" class="pl-3">Editar Personal</h6>
          <h6 v-else class="pl-3">Nuevo Personal</h6>
        </v-card-title>
        <v-card-text>
          <v-col cols="12">
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-sheet elevation="2">
                <v-tabs
                  v-model="model"
                  background-color="cyan"
                  dark
                  next-icon="mdi-arrow-right-bold-box-outline"
                  prev-icon="mdi-arrow-left-bold-box-outline"
                  show-arrows
                >
                  <v-tabs-slider color="yellow"></v-tabs-slider>
                  <v-tab v-for="i in 2" :key="i" :href="`#tab-${i}`">
                    <v-card-text v-if="i == 1">DATOS PERSONALES</v-card-text>
                    <v-card-text v-else>DATOS LABORALES</v-card-text>
                  </v-tab>
                </v-tabs>
              </v-sheet>
              <v-tabs-items v-model="model">
                <v-tab-item v-for="i in 2" :key="i" :value="`tab-${i}`">
                  <v-card flat>
                    <v-card-text v-if="i == 1">
                      <div align="center">
                        <br />
                        <h2>DATOS PERSONALES</h2>
                      </div>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Nombres"
                            :rules="[fieldRules.required]"
                            label="Nombres*"
                            type="text"
                            prepend-icon="mdi-border-color"
                          >
                          </v-text-field>
                        </v-col>

                        <v-col cols="6">
                          <v-text-field
                            v-model="ApellidoPaterno"
                            :rules="[fieldRules.required]"
                            label="Apellido Paterno*"
                            prepend-icon="mdi-border-color"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="ApellidoMaterno"
                            :rules="[fieldRules.required]"
                            label="Apellido Materno*"
                            type="text"
                            prepend-icon="mdi-border-color"
                          >
                          </v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-combobox
                            v-model="Siglas"
                            :items="auxListaSNP"
                            label="Sistema Nacional de Pensiones*"
                            prepend-icon="mdi-domain"
                          ></v-combobox>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-combobox
                            v-model="Documento"
                            :items="auxListaDocumento"
                            :rules="[fieldRules.required, fieldRules.validarDP]"
                            type="text"
                            label="Tipo de Documento Personal*"
                            prepend-icon="mdi-account-box"
                          >
                          </v-combobox>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="NumeroDocumento"
                            :rules="[fieldRules.validarCantidadNro]"
                            label="N° Documento*"
                            type="number"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Direccion"
                            :rules="[fieldRules.required]"
                            label="Dirección*"
                            prepend-icon="mdi-domain"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Telefono"
                            label="Teléfono"
                            type="text"
                            maxlength="10"
                            prepend-icon="mdi-phone"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Correo"
                            :rules="[fieldRules.required, fieldRules.email]"
                            label="Correo*"
                            prepend-icon="mdi-email"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Celular"
                            :rules="[fieldRules.required, fieldRules.numCel]"
                            label="Celular*"
                            type="text"
                            maxlength="9"
                            prepend-icon="mdi-cellphone-iphone"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-card-text>
                    <v-card-text v-else>
                      <div align="center">
                        <br />
                        <h2>DATOS LABORALES</h2>
                      </div>
                      <v-row>
                        <v-col cols="6">
                          <v-combobox
                            v-model="Local"
                            :items="localNombre"
                            :rules="[fieldRules.required, fieldRules.vallocal]"
                            type="text"
                            label="Local*"
                            prepend-icon="mdi-home"
                          >
                          </v-combobox>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Cargo"
                            :rules="[fieldRules.required]"
                            label="Cargo*"
                            type="text"
                            maxlength="9"
                            prepend-icon="mdi-account"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="Sueldo"
                            :rules="[fieldRules.required]"
                            label="Sueldo*"
                            prepend-icon="mdi-currency-usd"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="6">
                          <v-text-field
                            v-model="CorreoEmpresarial"
                            :rules="[fieldRules.required, fieldRules.email]"
                            label="Correo empresarial*"
                            type="text"
                            prepend-icon="mdi-email"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="6">
                          <v-text-field
                            v-model="CelularEmpresarial"
                            :rules="[fieldRules.required, fieldRules.numCel]"
                            label="Celular empresarial*"
                            type="text"
                            maxlength="9"
                            prepend-icon="mdi-cellphone-iphone"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-tab-item>
              </v-tabs-items>
            </v-form>
          </v-col>
          <span class="red--text">(*) Campos Obligatorios</span>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="indigo darken-4"
            text
            @click="((dialogEditar = false), (edit = false)), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            color="indigo darken-4"
            class="ma-2 white--text"
            @click="executeEventClick"
            >Cuardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-col cols="12" align="end">
      <v-btn
        color="indigo darken-4 white--text"
        elevation="5"
        @click="dialogEjemplo = true"
      >
        <v-icon left dark>mdi-plus</v-icon>
        Personal
      </v-btn>
    </v-col>
    <v-card style="border-top: 5px solid #1a237e !important">
      <v-card-title>
        <v-spacer></v-spacer>
        <v-row>
          <v-col cols="3">
            <v-combobox
              v-model="bopcion"
              :rules="[fieldRules.valicbo]"
              :items="listaOpciones"
              label="Opciones"
              prepend-icon="mdi-view-headline"
            ></v-combobox>
          </v-col>
          <v-col cols="6">
            <v-text-field
              v-model="txtBuscar"
              append-icon="mdi-magnify"
              :rules="[fieldRules.verBuscar]"
              label="Buscar"
              single-line
              hide-details
              v-on:keyup.enter="fn_buscarPersonal"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-card-title>
      <v-data-table :headers="headers" :items="listapersonal01">
        <template v-slot:[`item`]="{ item }">
          <tr v-bind:style="item.Vigencia ? '' : 'color: red;'">
            <td>{{ item.contador }}</td>
            <td>
              {{ item.ApellidoPaterno }} {{ item.ApellidoMaterno }}
              {{ item.Nombres }}
            </td>
            <td>{{ item.Documento }} {{ item.NumeroDocumento }}</td>
            <td>{{ item.Celular }}</td>
            <td>{{ item.nomlocal }}</td>
            <td>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="showEditPersonal(item)"
                    color="blue-grey"
                  >
                    mdi-border-color
                  </v-icon>
                </template>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="deletePersonal(item)"
                    :color="item.Vigencia ? 'red lighten-1' : 'green'"
                  >
                    {{
                      item.Vigencia
                        ? "mdi-close-circle-outline"
                        : "mdi-checkbox-marked-circle-outline"
                    }}
                  </v-icon>
                </template>
                <span>{{ item.Vigencia ? "Dar de baja" : "Dar de alta" }}</span>
              </v-tooltip>
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import { mapState } from "vuex";
import { put, patch, get, post } from "../api/api";
import Swal from "sweetalert2";
export default {
  data() {
    return {
      txtBuscar: "",
      search: "",
      edit: false,
      alert: false,
      alert02: true,
      dialogEjemplo: false,
      dialogEjemplo02: false,
      dialogEditar: false,
      valid: false,
      editID: null,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
        email: (v) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(v) || "Correo electrónico incorrecto.";
        },
        validarDP: (v) => {
          this.tipoDoc(v);
          return true;
        },
        validarCantidadNro: (v) => {
          this.cantidadNroActual = v.length;
          if (
            this.cantidadNroActual == this.maxCantidaNroDocumento ||
            this.cantidadNroActual == this.minCantidaNroDocumento
          ) {
            return true;
          } else {
            return "Verificar campo requerido";
          }
        },
        verBuscar: (v) => {
          if (v.length > 0) {
            if (this.bopcion == "Nombres" || this.bopcion == "Documento") {
              return true;
            } else {
              this.bopcion = "Opciones";
              return false;
            }
          } else {
            return false;
          }
        },
        valicbo: (v) => {
          if (v == "Nombres" || v == "Documento") {
            return true;
          } else {
            return "seleccionar una opción";
          }
        },
        numCel: (v) => {
          if (v.length == 9) {
            return true;
          } else {
            return "Verificar campo requerido";
          }
        },
        vallocal: (v) => {
          let pos = 0;
          pos = this.validarLocal(v);
          if (pos == 1) {
            return true;
          } else {
            return "Verificar campo";
          }
        },
      },
      headers: [
        {
          text: "N°",
          value: "Codigo",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Nombres",
          value: "Nombres",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Documento",
          value: "Documento",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Celular",
          value: "Celular",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Local",
          value: "Local",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Acciones",
          value: "actions",
          width: "15%",
          class: "indigo darken-4 white--text",
        },
      ],
      listapersonal01: [],
      Nombres: "",
      ApellidoPaterno: "",
      ApellidoMaterno: "",
      NumeroDocumento: "",
      Direccion: "",
      Telefono: "",
      Correo: "",
      Celular: "",
      Siglas: "",
      Documento: "",
      Local: "",
      Cargo: "",
      Sueldo: "",
      CorreoEmpresarial: "",
      CelularEmpresarial: "",
      UsuarioCodigoLocal: "",
      arrayLocal: [],
      localCodigo: [],
      localNombre: [],
      idLocal: "",
      fechahoy: "",

      maxCantidaNroDocumento: 0,
      cantidadNroActual: 0,
      listaDocumento: [],
      auxListaDocumento: [],
      auxListaSNP: [],
      validSNPCod: [],
      auxvalidDoc: [],
      listaOpciones: ["Nombres", "Documento"],
      bopcion: "",
      valor: "",
      auxDocumento: "",
      auxSiglas: "",
      minCantidaNroDocumento: 0,

      model: "tab-2",
      text1:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
      titu01: false,
      titu02: true,
      text2: "jose",
    };
  },
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    limpiar() {
      (this.Nombres = ""),
        (this.ApellidoPaterno = ""),
        (this.ApellidoMaterno = ""),
        (this.NumeroDocumento = ""),
        (this.Direccion = ""),
        (this.Telefono = ""),
        (this.Correo = ""),
        (this.Celular = ""),
        (this.Siglas = ""),
        (this.Documento = ""),
        (this.auxDocumento = ""),
        (this.auxSiglas = ""),
        (this.Local = ""),
        (this.Cargo = ""),
        (this.Sueldo = "");
      (this.CorreoEmpresarial = ""),
        (this.CelularEmpresarial = ""),
        this.$refs.form.resetValidation();
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerEmpresa();
      } else {
        this.editEmpresa();
      }
    },
    registerEmpresa() {
      let ba = 0;
      ba = this.validarFormulario();
      if (ba == 0) {
        Swal.fire({
          position: "top-center",
          title: "Sistema",
          text: "Existen  errores en el formulario",
          icon: "error",
          confirmButtonText: "Ok",
          timer: 2500,
        });
      } else {
        let ban = 0;
        ban = this.validarSNP();
        if (ban == 0) {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Existen  errores en el formulario",
            icon: "error",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        } else {
          let ban02 = 1;
          ban02 = this.validarDoc();
          ban02 = 1;
          if (ban02 == 0) {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "Existen  errores en el formulario",
              icon: "error",
              confirmButtonText: "Ok",
              timer: 2500,
            });
          } else {
            let ban03 = 0;
            ban03 = this.validarDatosLaborales();
            if (ban03 == 0) {
              Swal.fire({
                position: "top-center",
                title: "Sistema",
                text: "Existen  errores en el formulario",
                icon: "error",
                confirmButtonText: "Ok",
                timer: 2500,
              });
            } else {
              post("personal", this.assemblePersonal())
                .then(() => {
                  Swal.fire({
                    position: "top-center",
                    title: "Sistema",
                    text: "Personal registrado exitosamente",
                    icon: "success",
                    confirmButtonText: "Ok",
                    timer: 2500,
                  });
                  this.dialogEjemplo = false;
                  this.limpiar();
                })
                .catch(() => {
                  Swal.fire({
                    position: "top-center",
                    title: "Sistema",
                    text: "Existen  errores en el formulario",
                    icon: "error",
                    confirmButtonText: "Ok",
                    timer: 2500,
                  });
                  this.saveLoading = false;
                });
              this.listapersonal01 = [];
            }
          }
        }
      }
    },
    assemblePersonal() {
      return {
        Nombres: this.Nombres,
        ApellidoPaterno: this.ApellidoPaterno,
        ApellidoMaterno: this.ApellidoMaterno,
        Documento: this.auxDocumento,
        NumeroDocumento: this.NumeroDocumento,
        Direccion: this.Direccion,
        Telefono: this.Telefono,
        Correo: this.Correo,
        Celular: this.Celular,
        Siglas: this.auxSiglas,

        idLocal: this.idLocal,
        Cargo: this.Cargo,
        Sueldo: this.Sueldo,
        CorreoEmpresarial: this.CorreoEmpresarial,
        CelularEmpresarial: this.CelularEmpresarial,
        FechaInicio: this.fechahoy,
        FechaFin: this.fechahoy,
      };
    },
    validarSNP() {
      let va = 1;
      if (this.Celular.length < 9 || this.Celular.length > 9) {
        va = 0;
      }
      return va;
    },
    validarDoc() {
      let va = 0;
      let cam1 = 0;
      for (let i = 0; i < this.auxListaDocumento.length; i++) {
        if (this.auxListaDocumento[i] == this.Documento) {
          cam1 = 1;
        }
      }
      for (let i = 0; i < this.auxListaSNP.length; i++) {
        if (this.auxListaSNP[i] == this.Siglas) {
          this.auxSiglas = this.validSNPCod[i];
        }
      }

      if (cam1 == 1) {
        va = 1;
        for (let i = 0; i < this.auxListaDocumento.length; i++) {
          if (this.auxListaDocumento[i] == this.Documento) {
            this.auxDocumento = this.auxvalidDoc[i];
            va = 1;
          }
        }
      } else {
        va = 0;
      }
      return va;
    },
    validarFormulario() {
      let ban = 0;
      if (
        this.Nombres == "" ||
        this.ApellidoPaterno == "" ||
        this.ApellidoMaterno == "" ||
        this.NumeroDocumento.length != this.maxCantidaNroDocumento ||
        this.NumeroDocumento.length != this.minCantidaNroDocumento ||
        this.Direccion == "" ||
        this.Correo == "" ||
        this.Celular == ""
      ) {
        ban = 0;
      } else {
        ban = 1;
      }
      return ban;
    },
    validarFormulario02() {
      let ban = 0;
      if (
        this.Nombres == "" ||
        this.ApellidoPaterno == "" ||
        this.ApellidoMaterno == "" ||
        this.Direccion == "" ||
        this.Correo == "" ||
        this.Celular == ""
      ) {
        ban = 0;
      } else {
        ban = 1;
      }
      return ban;
    },
    async tipoDoc(tipo) {
      const tipoD = await get("documentopersonalvigentes");
      for (let i = 0; i < tipoD.length; i++) {
        if (tipoD[i].Nombre == tipo) {
          this.maxCantidaNroDocumento = tipoD[i].CantidadMaxima;
          this.minCantidaNroDocumento = tipoD[i].CantidadMinima;
        }
      }
      if (this.edit != true) {
        this.NumeroDocumento = false;
      }
    },
    showEditPersonal(personal) {
      this.edit = true;
      this.editID = personal.Codigo;
      this.mostrarPersonal(personal.Codigo).then(() => {
        this.mostrarPersonalLaboral(personal.Codigo).then(() => {
          this.dialogEditar = true;
        });
      });
    },
    async mostrarPersonal(codigo) {
      const personal = await get("personal/" + codigo);
      this.Nombres = personal.Nombres;
      this.ApellidoPaterno = personal.ApellidoPaterno;
      this.ApellidoMaterno = personal.ApellidoMaterno;
      this.Siglas = personal.CodigoSistemaPensiones;
      for (let i = 0; i < this.auxListaSNP.length; i++) {
        if (this.validSNPCod[i] == this.Siglas) {
          this.Siglas = this.auxListaSNP[i];
        }
      }
      this.Documento = personal.CodigoDocumentoPersona;
      for (let i = 0; i < this.auxListaDocumento.length; i++) {
        if (this.auxvalidDoc[i] == this.Documento) {
          this.Documento = this.auxListaDocumento[i];
        }
      }
      this.NumeroDocumento = personal.NumeroDocumento;
      this.Direccion = personal.Direccion;
      this.Telefono = personal.Telefono;
      this.Correo = personal.Correo;
      this.Celular = personal.Celular;
    },
    async mostrarPersonalLaboral(codigo) {
      const Localpersonal = await get("localpersonal/" + codigo);
      this.Cargo = Localpersonal.Cargo;
      this.Sueldo = Localpersonal.Sueldo;
      this.CorreoEmpresarial = Localpersonal.CorreoEmpresarial;
      this.CelularEmpresarial = Localpersonal.CelularEmpresarial;
      this.Local = Localpersonal.CodigoLocal;
      for (let i = 0; i < this.localCodigo.length; i++) {
        if (this.localCodigo[i] == this.Local) {
          this.Local = this.localNombre[i];
        }
      }
    },
    editEmpresa() {
      let ba = 0;
      ba = this.validarFormulario02();
      if (ba == 0) {
        Swal.fire({
          position: "top-center",
          title: "Sistema",
          text: "Existen  errores en el formulario",
          icon: "error",
          confirmButtonText: "Ok",
          timer: 2500,
        });
      } else {
        let ban = 0;
        ban = this.validarSNP();
        if (ban == 0) {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Existen  errores en el formulario",
            icon: "error",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        } else {
          let ban02 = 0;
          ban02 = this.validarDoc();
          console.log(ban02);
          if (ban02 == 0) {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "Existen  errores en el formulario",
              icon: "error",
              confirmButtonText: "Ok",
              timer: 2500,
            });
          } else {
            put("personal/" + this.editID, this.assemblePersonal())
              .then(() => {
                Swal.fire({
                  position: "top-center",
                  title: "Sistema",
                  text: "Personal actualizado exitosamente",
                  icon: "success",
                  confirmButtonText: "Ok",
                  timer: 2500,
                });

                this.dialogEjemplo = false;
                this.dialogEditar = false;
                this.editID = null;
                this.limpiar();
                this.fn_buscarPersonal();
              })
              .catch(() => {
                Swal.fire({
                  position: "top-center",
                  title: "Sistema",
                  text: "Existen  errores en el formulario",
                  icon: "error",
                  confirmButtonText: "Ok",
                  timer: 2500,
                });
                this.saveLoading = false;
              });
            this.listapersonal01 = [];
          }
        }
      }
    },
    deletePersonal(personal) {
      patch("personal/" + personal.Codigo)
        .then((data) => {
          if (data.Vigencia == 1) {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "Personal dado de alta exitosamente",
              icon: "success",
              confirmButtonText: "Ok",
              timer: 2500,
            });
          } else {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "Personal dado de baja exitosamente ",
              icon: "success",
              confirmButtonText: "Ok",
              timer: 2500,
            });
          }
          this.listapersonal01 = [];
          this.fn_buscarPersonal();
        })
        .catch(() => {
          this.alert = true;
        });
    },
    listaCboDocumento() {
      get("documentopersonalvigentes").then((data) => {
        this.listaDocumento = data;
        for (let i = 0; i < this.listaDocumento.length; i++) {
          this.auxListaDocumento.push(this.listaDocumento[i].Nombre);
          this.auxvalidDoc.push(this.listaDocumento[i].Codigo);
        }
      });
    },
    listaCboSNP() {
      get("sistemadepensionesvigentes").then((data) => {
        this.listaDocumento = data;
        for (let i = 0; i < this.listaDocumento.length; i++) {
          this.auxListaSNP.push(this.listaDocumento[i].Siglas);
          this.validSNPCod.push(this.listaDocumento[i].Codigo);
        }
      });
    },
    fn_buscarPersonal() {
      if (this.bopcion == "Nombres" || this.bopcion == "Documento") {
        if (this.txtBuscar != "") {
          this.valor = this.txtBuscar;
          for (let i = 0; i < this.auxListaDocumento.length; i++) {
            if (this.auxListaDocumento[i] == this.valor) {
              this.valor = this.auxvalidDoc[i];
            }
          }
          post("personal/" + this.valor + "/" + this.bopcion)
            .then((data) => {
              this.listapersonal01 = data;
            })
            .catch(() => {
              this.listapersonal01 = [];
            });
        }
      }
    },
    fun_verusuario() {
      this.UsuarioCodigoLocal = this.idLocal;
      this.listarCboLocal(this.UsuarioCodigoLocal);
    },
    listarCboLocal(id) {
      get("localusuario/" + id).then((data) => {
        this.arrayLocal = data;
        for (let i = 0; i < this.arrayLocal.length; i++) {
          this.localCodigo.push(this.arrayLocal[i].Codigo);
          this.localNombre.push(this.arrayLocal[i].Nombre);
        }
      });
      var hoy = new Date();
      var dd = hoy.getDate();
      var mm = hoy.getMonth() + 1;
      var yyyy = hoy.getFullYear();
      this.fechahoy = yyyy + "-" + mm + "-" + dd;
      console.log(this.fechahoy);
    },
    validarLocal(text) {
      for (let i = 0; i < this.localNombre.length; i++) {
        if (this.localNombre[i] == text) {
          this.idLocal = this.localCodigo[i];
          return 1;
        }
      }
      return 0;
    },
    validarDatosLaborales() {
      if (this.Local == "" || this.Cargo == "" || this.Sueldo == "") {
        return 0;
      }
      return 1;
    },
  },
  created() {
    this.idLocal = this.user.local;
    this.listaCboDocumento();
    this.listaCboSNP();
    this.fun_verusuario();
  },
};
</script>