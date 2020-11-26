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
          @click="dialog = true"
        >
          <v-icon>mdi-plus</v-icon>
          Producto
        </v-btn>
      </v-col>
    </v-row>
    <v-dialog v-model="dialog" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white"
            >Editar Producto</span
          >
          <span v-else class="headline" style="color: white"
            >Nuevo Producto</span
          >
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="CodigoCategoria"
                  :rules="[fieldRules.required]"
                  label="Código Categoria *"
                  hint="*Campo requerido"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="CodigoMarca"
                  :rules="[fieldRules.required]"
                  label="Código Marca *"
                  hint="*Campo requerido"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre *"
                  prepend-icon="mdi-nombre"
                ></v-text-field>
              </v-col>
              <v-col class="d-flex" cols="12" sm="6">
                <v-select
                  :items="itemsTipo"
                  v-model="Tipo"
                  :rules="[fieldRules.required]"
                  label="Tipo *"
                  outlined
                ></v-select>
              </v-col>
            </v-row>

            <v-row>
              <v-col class="d-flex" cols="12" sm="6">
                <v-select
                  :items="itemsTipoControl"
                  v-model="TipoControl"
                  :rules="[fieldRules.required]"
                  label="Tipo Control *"
                  outlined
                ></v-select>
              </v-col>
              <!------------------------------------------------------------------------- - -->
              <v-checkbox
                v-model="checkbox"
                :label="`Negociable: ${checkbox.toString()}`"
              ></v-checkbox>
              <!------------------------------------------------------------------------- - -->
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
                      label="Buscar"
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
        },
        {
          text: "Nombre Producto",
          align: "start",
          value: "nombreProducto",
          width: "20%",
          sortable: false,
        },
        {
          text: "Categoria",
          value: "nombreCategoria",
          width: "20%",
          sortable: false,
        },
        {
          text: "Marca",
          value: "nombreMarca",
          width: "20%",
          sortable: false,
        },
        {
          text: "Tipo",
          value: "Tipo",
          width: "20%",
          sortable: false,
        }, //INCOMPLETO
        {
          text: "Acciones",
          value: "actions",
          width: "20%",
          sortable: false,
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
      //this.ver = false;
      this.edit = false;
      this.indiceEditar = -1;
      this.$refs.form.resetValidation();
    },
    guardar() {
      if (this.indiceEditar === -1) {
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
        Negociable: this.Negociable,
      };
    },
    registrar() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("productos", this.getProducto())
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
          this.refrescarProducto();
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
      this.mostrarProducto(producto.Codigo).then(() => {
        this.dialog = true;
      });
    },

    async mostrarProducto(codigo) {
      const producto = await get("producto/" + codigo);
      this.CodigoCategoria = producto.CodigoCategoria;
      this.CodigoMarca = producto.CodigoMarca;
      this.Tipo = producto.Tipo;
      this.Nombre = producto.Nombre;
      this.TipoControl = producto.TipoControl;
      //this.Negociable = producto.Negociable;
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