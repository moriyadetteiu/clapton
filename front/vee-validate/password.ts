import { extend } from 'vee-validate'

extend('password', {
  params: ['target'],
  validate(value, { target }: any) {
    return value === target
  },
  message: 'パスワードとパスワード確認が一致していません',
})
