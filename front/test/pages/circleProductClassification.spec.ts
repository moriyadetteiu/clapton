import CircleProductClassification from '~/pages/teams/_team_id/circle-product-classifications.vue'
import CircleProductClassificationFactory from '~/test/factory/CircleProductClassificationFactory'
import { testMasterPage } from './masterPageTest'

describe('circle product classification page', () => {
  const factory = new CircleProductClassificationFactory()
  testMasterPage(factory, CircleProductClassification, ['name'])
})
