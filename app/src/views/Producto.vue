<template>
  <v-content>
    <v-row class="pa-2" align="center">
      <v-col cols="12">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Productos
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
          <v-icon>mdi-plus</v-icon>
          Producto
        </v-btn>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" persistent scrollable max-width="60vw">
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
          <h6 v-if="edit" class="pl-3">Editar Producto</h6>
          <h6 v-else class="pl-3">Nuevo Producto</h6>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre *"
                  prepend-icon="mdi-note-text"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-select
                  :items="optionsMarca"
                  v-model="CodigoMarca"
                  label="Marca"
                  prepend-icon="mdi-tag"
                  dense
                  :rules="[fieldRules.required]"
                ></v-select>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="6">
                <v-select
                  :items="optionsCategoria"
                  v-model="CodigoCategoria"
                  label="Categoria"
                  prepend-icon="mdi-clipboard"
                  dense
                  :rules="[fieldRules.required]"
                ></v-select>
              </v-col>
              <v-col class="d-flex" cols="12" sm="6">
                <v-select
                  :items="itemsTipo"
                  v-model="Tipo"
                  :rules="[fieldRules.required]"
                  label="Tipo *"
                  prepend-icon="mdi-buffer"
                  dense
                ></v-select>
              </v-col>
            </v-row>

            <v-row>
              <v-col class="d-flex" cols="6">
                <v-select
                  :items="itemsTipoControl"
                  v-model="TipoControl"
                  :rules="[fieldRules.required]"
                  label="Control *"
                  prepend-icon="mdi-book-open-page-variant"
                  class="pt-2"
                  dense
                ></v-select>
              </v-col>
              <v-col class="d-flex" cols="6">
                <v-checkbox
                  v-model="checkbox"
                  :label="`Negociable`"
                ></v-checkbox>
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
            @click="(dialog = false), limpiar()"
            >Cancelar</v-btn
          >
          <v-btn
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="guardar"
            >Guardar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-col class="pa-1" align="center">
      <v-col cols="10" align="center">
        <v-card style="border-top: 5px solid #1a237e !important">
          <v-card-title>
            <v-row align="center" justify-center class="text-center pb-0 pl-10">
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-row align="center" justify-center class="text-center">
                  <v-col
                    class="justify-content-center text-center"
                    align="center"
                    cols="6"
                  >
                    <v-select
                      v-model="bopcion"
                      :rules="[fieldRules.required]"
                      :items="listaOpciones"
                      label="Opciones"
                      class="pt-8"
                    ></v-select>
                  </v-col>
                  <v-col
                    class="justify-content-center text-center"
                    align="center"
                    cols="5"
                  >
                    <v-text-field
                      v-model="txtBuscar"
                      :rules="[fieldRules.required]"
                      label="Búsqueda"
                      class="pt-8"
                      single-line
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
                      @click="refrescarProducto"
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
            :items="producto"
            :item-class="itemFilaColor"
          >
            <template v-slot:[`item.index`]="{ item }">
              {{ producto.indexOf(item) + 1 }}
            </template>
            <template v-slot:[`item.actions`]="{ item }">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    v-bind="attrs"
                    v-on="on"
                    class="mr-2"
                    color="blue-grey"
                    @click="leer(item)"
                    >mdi-border-color
                  </v-icon>
                </template>
                <span>Editar</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    v-bind="attrs"
                    v-on="on"
                    class="mr-2"
                    :color="item.Vigencia ? 'green' : 'red lighten-1'"
                    @click="cambiarVigencia(item)"
                    >{{
                      item.Vigencia
                        ? "mdi-checkbox-marked-circle-outline"
                        : "mdi-close-circle-outline"
                    }}</v-icon
                  >
                </template>
                <span>{{ item.Vigencia ? "Dar de alta" : "Dar de baja" }}</span>
              </v-tooltip>
            </template>
          </v-data-table>
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
      txtBuscar: "",
      edit: false,
      valid: true,
      saveLoading: false,
      dialog: false,
      checkbox: false,
      optionsCategoria: [],
      optionsMarca: [],
      itemsTipo: [
        { value: "B", text: "Bien", name: "Bien" },
        { value: "S", text: "Servicio", name: "Servicio" },
      ],

      producto: [],
      itemsTipoControl: ["PEPS", "Promedio", "Identificación única"],

      fieldRules: {
        required: (v) => !!v || "Campo requerido",
      },

      headers: [
        {
          text: "N°",
          value: "index",
          width: "10%",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Nombre Producto",
          align: "start",
          value: "nombreProducto",
          width: "20%",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Categoria",
          value: "nombreCategoria",
          width: "20%",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Marca",
          value: "nombreMarca",
          width: "20%",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
        {
          text: "Tipo",
          value: "Tipo",
          width: "20%",
          sortable: false,
          class: "indigo darken-4 white--text",
        }, //INCOMPLETO
        {
          text: "Acciones",
          value: "actions",
          width: "20%",
          sortable: false,
          class: "indigo darken-4 white--text",
        },
      ],
      options: [
        {
          name: "Editar",
          icon: "mdi-pencil",
          function: this.showEditProducto,
        },
        {
          name: "Eliminar",
          icon: "mdi-delete",
          function: this.deleteProducto,
        },
        {
          name: "CambiarVigencia",
          icon: "mdi-check-box-outline",
          function: this.cambiarVigencia,
        },
      ],

      productos: "",
      search: "",
      CodigoCategoria: "",
      CodigoMarca: "",
      Tipo: "",
      Nombre: "",
      TipoControl: "",
      CodigoTipo: "",
      listaOpciones: [
        { text: "Nombre", value: "p.Nombre" },
        { text: "Categoria", value: "c.Nombre" },
        { text: "Marca", value: "m.Nombre" },
      ],
      bopcion: "",
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
      this.CodigoCategoria = "";
      this.CodigoMarca = "";
      this.Tipo = "";
      this.Nombre = "";
      this.TipoControl = "";
      this.checkbox = false;
      this.edit = false;
      this.$refs.form.resetValidation();
    },
    abrirDialog() {
      this.dialog = true;
      this.mostrarCategoria();
      this.mostrarMarca();
    },
    guardar() {
      if (this.edit === false) {
        this.registrar();
      } else {
        this.editar();
      }
    },
    getProducto() {
      return {
        Nombre: this.Nombre,
        CodigoCategoria: this.CodigoCategoria,
        CodigoMarca: this.CodigoMarca,
        Tipo: this.Tipo,
        TipoControl: this.TipoControl,
        Negociable: this.checkbox,
      };
    },
    registrar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("producto", this.getProducto())
        .then(() => {
          this.saveLoading = false;
          this.dialog = false;
          this.limpiar();
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Producto registrado correctamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
        })
        .catch(() => {});
    },

    editar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("producto/" + this.codigoEditar, this.getProducto())
        .then(() => {
          this.saveLoading = false;
          this.codigoEditar = null;
          this.dialog = false;
          this.limpiar();
          Swal.fire({
            position: "top-center",
            title: "Sistema",
            text: "Producto actualizado correctamente.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 2500,
          });
          this.refrescarProducto();
        })
        .catch(() => {});
    },

    leer(producto) {
      this.indiceEditar = producto.Codigo;
      this.codigoEditar = producto.Codigo;
      this.edit = true;
      this.mostrarCategoria();
      this.mostrarMarca();
      this.mostrarProducto(producto.Codigo).then(() => {
        this.dialog = true;
      });
    },

    mostrarCategoria() {
      post("productocategoria", this.assembleEmpresa()).then((data) => {
        this.optionsCategoria = data;
      });
    },

    mostrarMarca() {
      get("productomarca").then((data) => {
        this.optionsMarca = data;
      });
    },
    async mostrarProducto(codigo) {
      const producto = await get("producto/" + codigo);
      this.CodigoCategoria = producto.CodigoCategoria;
      this.CodigoMarca = producto.CodigoMarca;
      this.Tipo = producto.Tipo;
      this.Nombre = producto.Nombre;
      this.TipoControl = producto.TipoControl;
      this.checkbox = producto.Negociable;
    },
    cambiarVigencia(producto) {
      patch("producto/" + producto.Codigo)
        .then((data) => {
          if (data.Vigencia == 1) {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "Producto dado de alta",
              icon: "success",
              confirmButtonText: "Ok",
              timer: 2500,
            });
          } else {
            Swal.fire({
              position: "top-center",
              title: "Sistema",
              text: "Producto dado de baja",
              icon: "success",
              confirmButtonText: "Ok",
              timer: 2500,
            });
          }
          this.refrescarProducto();
        })
        .catch(() => {
          this.saveLoading = false;
        });
    },
    assembleEmpresa() {
      return {
        empresa: this.empresa,
        atributo: this.bopcion,
        busqueda: this.txtBuscar,
      };
    },
    refrescarProducto() {
      if (this.valid == false) return;
      this.loading = true;
      post("productos", this.assembleEmpresa()).then((data) => {
        this.producto = data;
        this.loading = false;
      });
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },
  },

  created() {
    this.empresa = this.user.empresaId;
  },
};
</script>

<style>
</style>