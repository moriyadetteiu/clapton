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
import { config } from '@vue/test-utils'
import VIconStub from './stubs/VIcon'
import VValidateTextField from '~/components/form/VValidateTextField.vue'
import '~/vee-validate/password'

Vue.use(Vuetify)
Vue.use(Vuex)

localize({ ja })
localize('ja')

const { required, email } = require('vee-validate/dist/rules.umd')
extend('required', required)
extend('email', email)

Vue.component('ValidationObserver', ValidationObserver)
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('VValidateTextField', VValidateTextField)

config.stubs['v-icon'] = VIconStub
