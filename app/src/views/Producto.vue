<template>
  <v-container style="padding: 0px">
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn fab large dark color="blue" @click="dialog = true">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-regular text-center">
          Mantenimiento de Producto
        </h2>
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
    <v-card>
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
              @keyup.enter="fn_buscarProducto"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-card-title>
      <!--<v-col cols="12" class="pt-0">  NO SÉ POR QUE XD -->
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
  </v-container>
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
        verBuscar: (v) => {
          if (v.length > 0) {
            if (this.bopcion == "Nombre" || this.bopcion == "Codigo") {
              //this.fn_buscarPersonal(v, this.bopcion)
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
          if (v == "Nombre" || v == "Codigo") {
            return true;
          } else {
            return "seleccionar una opción";
          }
        },
      },

      headers: [
        {
          text: "N°",
          value: "index",
          width: "10%",
          class: "light blue darken-4 white--text",
          sortable: false,
        },
        {
          text: "Nombre Producto",
          align: "start",
          value: "nombreProducto",
          width: "20%",
          class: "light blue darken-4 white--text",
          sortable: false,
        },
        {
          text: "Categoria",
          value: "nombreCategoria",
          width: "20%",
          class: "light blue darken-4 white--text",
          sortable: false,
        },
        {
          text: "Marca",
          value: "nombreMarca",
          width: "20%",
          class: "light blue darken-4 white--text",
          sortable: false,
        },
        {
          text: "Tipo",
          value: "Tipo",
          width: "30%",
          class: "light blue darken-4 white--text",
          sortable: false,
        }, //INCOMPLETO
        {
          text: "Acciones",
          value: "actions",
          width: "20%",
          class: "light blue darken-4 white--text",
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
      indiceEditar: -1,
      listaproducto01: [],
      listaproductos: [],
      listaOpciones: ["Nombre", "Codigo"],
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
      };
    },
    refrescarProducto() {
      post("productos", this.assembleEmpresa()).then((data) => {
        this.producto = data;
        this.listaproductos = data;
      });
    },
    itemFilaColor: function (item) {
      return item.Vigencia ? "black--text" : "red--text";
    },

    fn_buscarProducto() {
      if (this.bopcion == "Nombre" || this.bopcion == "Codigo") {
        if (this.txtBuscar != "") {
          this.valor = this.txtBuscar;
          if (this.bopcion == "Codigo") {
            this.listaproducto01 = this.listaproductos.filter(
              (x) => x.Codigo == this.valor
            );
            this.producto = this.listaproducto01;
          } else {
            let er = new RegExp("." + this.valor + "*");
            console.log(er);
            this.listaproducto01 = this.listaproductos.filter((x) =>
              x.nombreProducto.match(er)
            );
            this.producto = this.listaproducto01;
          }
        } else {
          this.refrescarProducto();
        }
      }
    },
  },

  created() {
    this.empresa = this.user.empresaId;
    this.refrescarProducto();
  },
};
</script>

<style>
</style>