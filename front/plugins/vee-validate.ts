import Vue from 'vue';
import { ValidationObserver, ValidationProvider, localize } from 'vee-validate'
import ja from 'vee-validate/dist/locale/ja.json';
import { extend } from "vee-validate";
import { required, email } from "vee-validate/dist/rules";
import VValidateTextField from '~/components/form/VValidateTextField.vue'

localize({ ja });
localize('ja');

extend("required", required);
extend("email", email);

extend('password', {
  params: ['target'],
  validate(value, { target }: any) {
    return value === target;
  },
  message: 'パスワードとパスワード確認が一致していません'
});

Vue.component('validation-observer', ValidationObserver);
Vue.component('validation-provider', ValidationProvider);
Vue.component('v-validate-text-field', VValidateTextField);
