import CirclePlacementClassification from '~/pages/teams/_team_id/circle-placement-classifications.vue'
import CirclePlacementClassificationFactory from '~/test/factory/CirclePlacementClassificationFactory'
import { testMasterPage } from './masterPageTest'

describe('circle placement classification page', () => {
  const factory = new CirclePlacementClassificationFactory()
  testMasterPage(factory, CirclePlacementClassification, ['name', 'cost'])
})
