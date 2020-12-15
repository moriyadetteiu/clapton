import axios from 'axios'
import fs from 'fs'

// TODO: .envを参照するようにする
const ENDPOINT: string = 'http://nginx'
const downloadValidationUrl = `${ENDPOINT}/api/develop/export-validation`

const template: string = fs
  .readFileSync(`${__dirname}/validation.template`)
  .toString()

const indentLevel: number = 6

axios.get(downloadValidationUrl).then((result) => {
  const data: Object = result.data
  const validations: string = Object.entries(data)
    .map((validation) => {
      const [name, items] = validation

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
