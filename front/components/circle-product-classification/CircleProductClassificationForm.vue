<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import {
  UpdateCircleProductClassificationMutation,
  CreateCircleProductClassificationMutation,
  CircleProductClassification,
  CircleProductClassificationInput,
} from '~/apollo/graphql'
import { CreateCircleProductClassificationInputValidation } from '~/validation/validations'
import AbstractMasterForm, {
  inputField,
} from '~/components/masters/AbstractMasterForm.vue'

@Component({})
export default class CircleProductClassificationForm extends AbstractMasterForm<
  CircleProductClassification,
  CircleProductClassificationInput
> {
  protected readonly inputFields: inputField[] = [{ name: 'name' }]

  protected readonly validation: CreateCircleProductClassificationInputValidation = new CreateCircleProductClassificationInputValidation()
  protected readonly title: string = '頒布物分類'
  protected readonly createMutation = CreateCircleProductClassificationMutation
  protected readonly updateMutation = UpdateCircleProductClassificationMutation

  protected input: CircleProductClassificationInput = {
    team_id: '',
    name: '',
  }
}
</script>
