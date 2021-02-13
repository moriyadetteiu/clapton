import CirclePlacementClassificationForm from '~/components/circle-placement-classification/CirclePlacementClassificationForm.vue'
import CirclePlacementClassificationFactory from '~/test/factory/CirclePlacementClassificationFactory'
import { testMasterForm } from '~/test/components/masters/MasterFormTest'

describe('circle placement classification form', () => {
  const factory = new CirclePlacementClassificationFactory()
  testMasterForm(factory, CirclePlacementClassificationForm, {
    team_id: '',
    name: '',
    cost: 0,
  })
})
