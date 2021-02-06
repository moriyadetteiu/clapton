import WantPriorityForm from '~/components/want-priority/WantPriorityForm.vue'
import WantPriorityFactory from '~/test/factory/WantPriorityFactory'
import { testMasterForm } from '~/test/components/masters/MasterFormTest'

describe('want priority form', () => {
  const factory = new WantPriorityFactory()
  testMasterForm(factory, WantPriorityForm, {
    team_id: '',
    name: '',
  })
})
