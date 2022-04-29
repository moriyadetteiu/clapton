import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import {
  extend,
  localize,
  ValidationObserver,
  ValidationProvider,
} from 'vee-validate'
import ja from 'vee-validate/dist/locale/ja.json'
import VValidateTextField from '~/components/form/VValidateTextField.vue'
import '~/vee-validate/password'

Vue.use(Vuetify)
Vue.use(Vuex)

localize({ ja })
localize('ja')

const { required, email } = require('vee-validate/dist/rules.umd')
extend('required', required)
extend('email', email)

Vue.component('validation-observer', ValidationObserver)
Vue.component('validation-provider', ValidationProvider)
Vue.component('v-validate-text-field', VValidateTextField)
