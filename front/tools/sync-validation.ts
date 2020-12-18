import axios from 'axios'
import fs from 'fs'

// TODO: .envを参照するようにする
const ENDPOINT: string = 'http://nginx'
const downloadValidationUrl = `${ENDPOINT}/api/develop/export-validation`

// TODO: vee-validationで登録しているものと連携させる
const ALLOWED_RULES = ['required', 'email']

const template: string = fs
  .readFileSync(`${__dirname}/validation.template`)
  .toString()

const indentLevel: number = 6

axios
  .get(downloadValidationUrl)
  .then((result) => {
    const data: Object = result.data
    const validations: string = Object.entries(data)
      .map((validation) => {
        const [name, items] = validation

        Object.keys(items).forEach((key) => {
          if (items[key]) {
            items[key].rules = items[key].rules
              .split('|')
              .filter((rule: string) => ~ALLOWED_RULES.indexOf(rule))
              .join('|')
          }
        })

        return template
          .replace('{{ name }}', name)
          .replace('{{ items }}', JSON.stringify(items, null, indentLevel))
      })
      .join('\n\n')

    const header = `import Validation from './validation'

`

    fs.writeFileSync('validation/validations.ts', `${header}${validations}`, {
      flag: 'w',
    })
    console.log('written to validation/validations.ts')
  })
  .catch((e) => {
    console.error(e)
    throw e
  })
