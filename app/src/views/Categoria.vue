<template>
  <v-content>
    <v-row class="pa-2" align="center">
      <v-col cols="12">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Categoria
        </h2>
      </v-col>
    </v-row>
    <v-row align="center">
      <v-col cols="11" align="end">
        <v-btn
          color="indigo darken-4 white--text"
          elevation="5"
          @click="dialogEjemplo = true"
        >
          <v-icon>mdi-plus</v-icon>
          Categoria
        </v-btn>
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
            >mdi-book</v-icon
          >
          <v-icon v-else style="color: #90a4ae !important">mdi-book</v-icon>
          <h6 v-if="edit" class="pl-3">Editar Categoria</h6>
          <h6 v-else class="pl-3">Nueva Categoria</h6>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombre"
                  label="Nombre *"
                  prepend-icon="mdi-domain"
                  maxlength="30"
                  :error-messages="errorsC"
                  @mouseup="limpiarError()"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Descripcion"
                  label="Descripción *"
                  maxlength="100"
                  prepend-icon="mdi-domain"
                  required
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
            @click="(dialogEjemplo = false), limpiar()"
            >Cancelar
          </v-btn>
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4 white--text"
            elevation="5"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
          >
            <v-icon left dark>mdi-content-save</v-icon>
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-col class="pa-1" align="center">
      <v-col cols="10" align="center">
        <v-card style="border-top: 5px solid #1a237e !important">
          <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-col cols="12" class="pt-0">
            <v-data-table
              :loading="tableLoading"
              :headers="headers"
              :items="categorias"
              :search="search"
            >
              <template v-slot:[`item`]="{ item }">
                <tr v-bind:class="item.Vigencia ? 'activo' : 'desactivo'">
                  <td>{{ categorias.indexOf(item) + 1 }}</td>
                  <td>{{ item.Nombre }}</td>
                  <td>{{ item.Descripcion }}</td>
                  <td>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          class="mr-2"
                          v-bind="attrs"
                          v-on="on"
                          color="blue-grey"
                          @click="showEditCategoria(item)"
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
                          @click="cambiarEstadoCategoria(item)"
                        >
                          {{
                            item.Vigencia
                              ? "mdi-close-circle-outline"
                              : "mdi-checkbox-marked-circle-outline"
                          }}
                        </v-icon>
                      </template>
                      <span>{{
                        item.Vigencia ? "Dar de baja" : "Dar de alta"
                      }}</span>
                    </v-tooltip>
                  </td>
                </tr>
              </template>
            </v-data-table>
          </v-col>
        </v-card>
      </v-col>
    </v-col>
  </v-content>
</template>

<script>
import { mapState } from "vuex";
import { get, post, put, patch } from "../api/api";
import Swal from "sweetalert2";
export default {
  data() {
    return {
      edit: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      tableLoading: true,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
      },
      headers: [
        {
          text: "N°",
          sortable: false,
          value: "index",
          width: "10%",
          class: "indigo darken-4 white--text",
        },
        {
          text: "Nombre",
          align: "start",
          value: "Nombre",
          width: "50%",
          class: "indigo darken-4 white--text",
        },
        {
          text: "Descripción",
          value: "Descripcion",
          width: "30%",
          class: "indigo darken-4 white--text",
        },
        {
          text: "Acciones",
          value: "actions",
          width: "20%",
          class: "indigo darken-4 white--text",
        },
      ],
      categorias: [],
      errorsC: [],
      search: "",
      CodigoEmpresa: "",
      Nombre: "",
      Descripcion: "",
      valor: "",
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
      this.CodigoEmpresa = "";
      this.Nombre = "";
      this.Descripcion = "";
      this.errorsC = [];
    },
    limpiarError() {
      this.errorsC = [];
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerCategoria();
      } else {
        this.editCategoria();
      }
    },
    assembleCategoria() {
      return {
        CodigoEmpresa: this.empresa,
        Nombre: this.Nombre,
        Descripcion: this.Descripcion,
      };
    },

    registerCategoria() {
      if (this.valid == false) return;
      this.saveLoading = true;

      post("categoria", this.assembleCategoria())
        .then(() => {
          this.saveLoading = false;
          this.dialogEjemplo = false;
          this.limpiar();
          Swal.fire({
            title: "Sistema",
            text: "Categoria registrada correctamente.",
            icon: "success",
            confirmButtonText: "OK",
          });
          this.actualizarCategorias();
        })
        .catch((e) => {
          if (e.message == 404) {
            this.errorsC = ["Categoria ya existe"];
          }
          this.saveLoading = false;
        });
    },
    editCategoria() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("categorias/" + this.editId, this.assembleCategoria())
        .then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.edit = false;
          this.limpiar();
          Swal.fire({
            title: "Sistema",
            text: "Categoria actualizada correctamente.",
            icon: "success",
            confirmButtonText: "OK",
          });
          this.actualizarCategorias();
        })
        .catch((e) => {
          if (e.message == 404) {
            this.errorsC = ["Categoria ya existe"];
          }
          this.saveLoading = false;
        });
    },
    showEditCategoria(categoria) {
      this.edit = true;
      this.editId = categoria.Codigo;
      this.mostrarCategoria(categoria.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    cambiarEstadoCategoria(categoria) {
      patch("categoria/" + categoria.Codigo).then((data) => {
        if (data.Vigencia == 1) {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Categoria habilitada con éxito",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        } else {
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Categoria dada de baja con éxito ",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
          });
        }
        this.actualizarCategorias();
      });
    },
    assembleEmpresa() {
      return {
        empresa: this.empresa,
      };
    },
    actualizarCategorias() {
      this.tableLoading = true;
      post("categorias", this.assembleEmpresa()).then((data) => {
        this.tableLoading = false;
        this.categorias = data;
      });
    },
    async mostrarCategoria(codigo) {
      const categoria = await get("categorias/" + codigo);
      this.CodigoEmpresa = 1;
      this.Nombre = categoria.Nombre;
      this.Descripcion = categoria.Descripcion;
    },
  },
  created() {
    this.empresa = this.user.empresaId;
    this.actualizarCategorias();
  },
};
</script>

<style>
tr.desactivo {
  color: red;
}
</style>