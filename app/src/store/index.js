import Vue from 'vue'
import Vuex from 'vuex'
import { getUser } from "../api/api";
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: getUser(),
    mensaje: "",
    admin: false,
    personal: false
  },
  mutations: {
    updateUser(state) {
      state.user = getUser();
      if (state.user != null) {
        state.admin = state.user.perfil === 1 ? true : false;
      }
    },
  },
})
