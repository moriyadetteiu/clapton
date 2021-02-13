import WantPriority from '~/pages/teams/_team_id/circle-product-classifications.vue'
import WantPriorityFactory from '~/test/factory/WantPriorityFactory'
import { testMasterPage } from './masterPageTest'

describe('want priority page', () => {
  const factory = new WantPriorityFactory()
  testMasterPage(factory, WantPriority, ['name'])
})
