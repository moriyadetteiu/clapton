import { mount } from '@vue/test-utils'
import VValidateTextField from '~/components/form/VValidateTextField.vue'

describe('VValidateTextField', () => {
  test('success login test', async () => {
    mount(VValidateTextField, {
      propsData: {
        label: 'テスト',
        rules: 'required',
      },
    })

    // TODO: ちゃんとしたテストを書く
  })
})
