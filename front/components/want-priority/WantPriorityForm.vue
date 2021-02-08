<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import {
  UpdateWantPriorityMutation,
  CreateWantPriorityMutation,
  WantPriority,
  WantPriorityInput,
} from '~/apollo/graphql'
import { CreateWantPriorityInputValidation } from '~/validation/validations'
import AbstractMasterForm, {
  inputField,
} from '~/components/masters/AbstractMasterForm.vue'

@Component({})
export default class WantPriorityForm extends AbstractMasterForm<
  WantPriority,
  WantPriorityInput
> {
  protected readonly inputFields: inputField[] = [{ name: 'name' }]

  protected readonly validation: CreateWantPriorityInputValidation = new CreateWantPriorityInputValidation()
  protected readonly title: string = '優先度'
  protected readonly createMutation = CreateWantPriorityMutation
  protected readonly updateMutation = UpdateWantPriorityMutation

  protected input: WantPriorityInput = {
    team_id: '',
    name: '',
  }
}
</script>
