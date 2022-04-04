import Validation from './validation'

export class LoginValidation extends Validation {
  constructor() {
    super({
      email: {
        rules: 'required|email',
        attribute: 'メールアドレス',
      },
      password: {
        rules: 'required',
        attribute: 'パスワード',
      },
    })
  }
}
