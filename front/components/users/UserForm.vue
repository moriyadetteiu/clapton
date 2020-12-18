<template>
  <validation-observer ref="validationObserver" tag="v-form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.name"
            :validation="validation.getItem('name')"
            outlined
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.name_kana"
            outlined
            :validation="validation.getItem('name_kana')"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.handle_name"
            outlined
            :validation="validation.getItem('handle_name')"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.handle_name_kana"
            outlined
            :validation="validation.getItem('handle_name_kana')"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.email"
            outlined
            :validation="validation.getItem('email')"
            hint="ログイン時や、パスワードを忘れた際に使用します。"
            persistent-hint
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.password"
            outlined
            :validation="validation.getItem('password')"
            type="password"
            vid="password"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="confirmationPassword"
            outlined
            label="パスワード確認"
            rules="required|password:@password"
            type="password"
          />
        </v-col>
        <v-col cols="12">
          <v-btn color="success" @click="submit">登録</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import { Vue, PropSync, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
import { ValidationObserver } from 'vee-validate'
import { UserInput } from '~/apollo/graphql'
import { CreateUserInputValidation } from '~/validation/validations'

@Component
export default class UserForm extends Vue {
  @PropSync('user', { type: Object as PropType<UserInput> } as PropOptions<
    UserInput
  >)
  private syncedUser!: UserInput

  private confirmationPassword: string = ''
  private validation: CreateUserInputValidation = new CreateUserInputValidation()

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      this.$emit('submit')
    }
  }
}
</script>
