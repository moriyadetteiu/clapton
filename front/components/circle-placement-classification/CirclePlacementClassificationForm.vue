<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import {
  UpdateCirclePlacementClassificationMutation,
  CreateCirclePlacementClassificationMutation,
  CirclePlacementClassification,
  CirclePlacementClassificationInput,
} from '~/apollo/graphql'
import { CreateCirclePlacementClassificationInputValidation } from '~/validation/validations'
import AbstractMasterForm, {
  inputField,
} from '~/components/masters/AbstractMasterForm.vue'

@Component({})
export default class CirclePlacementClassificationForm extends AbstractMasterForm<
  CirclePlacementClassification,
  CirclePlacementClassificationInput
> {
  protected readonly inputFields: inputField[] = [
    { name: 'name' },
    { name: 'cost' },
  ]

  protected readonly validation: CreateCirclePlacementClassificationInputValidation = new CreateCirclePlacementClassificationInputValidation()
  protected readonly title: string = '配置分類'
  protected readonly createMutation = CreateCirclePlacementClassificationMutation
  protected readonly updateMutation = UpdateCirclePlacementClassificationMutation

  protected input: CirclePlacementClassificationInput = {
    team_id: '',
    name: '',
    cost: 0,
  }
}
</script>
