import CircleProductClassificationForm from '~/components/circle-product-classification/CircleProductClassificationForm.vue'
import CircleProductClassificationFactory from '~/test/factory/CircleProductClassificationFactory'
import { testMasterForm } from '~/test/components/masters/MasterFormTest'

describe('circle product classification form', () => {
  const factory = new CircleProductClassificationFactory()
  testMasterForm(factory, CircleProductClassificationForm, {
    team_id: '',
    name: '',
  })
})
