import Vue from 'vue'
import {
  extend,
  ValidationObserver,
  ValidationProvider,
  localize,
} from 'vee-validate'
import ja from 'vee-validate/dist/locale/ja.json'
import { required, email } from 'vee-validate/dist/rules'
import VValidateTextField from '~/components/form/VValidateTextField.vue'
import '~/vee-validate/password'

localize({ ja })
localize('ja')

extend('required', required)
extend('email', email)

Vue.component('validation-observer', ValidationObserver)
Vue.component('validation-provider', ValidationProvider)
Vue.component('v-validate-text-field', VValidateTextField)
