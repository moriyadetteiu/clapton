<template>
  <validation-observer ref="validationObserver" tag="form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.name"
            :validation="validation.getItem('name')"
            :backend-errors="validation.getErrorMessages('name')"
            outlined
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.name_kana"
            outlined
            :validation="validation.getItem('name_kana')"
            :backend-errors="validation.getErrorMessages('name_kana')"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.handle_name"
            outlined
            :validation="validation.getItem('handle_name')"
            :backend-errors="validation.getErrorMessages('handle_name')"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.handle_name_kana"
            outlined
            :validation="validation.getItem('handle_name_kana')"
            :backend-errors="validation.getErrorMessages('handle_name_kana')"
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.email"
            outlined
            :validation="validation.getItem('email')"
            :backend-errors="validation.getErrorMessages('email')"
            hint="ログイン時や、パスワードを忘れた際に使用します。"
            persistent-hint
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="syncedUser.password"
            outlined
            :validation="validation.getItem('password')"
            :backend-errors="validation.getErrorMessages('password')"
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
import { isApolloError } from 'apollo-client/errors/ApolloError'
import { Vue, Prop, PropSync, Component } from 'nuxt-property-decorator'
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

  @Prop({ required: true, type: Function as PropType<() => Promise<void>> })
  private onSubmit!: () => Promise<void>

  private confirmationPassword: string = ''
  private validation: CreateUserInputValidation = new CreateUserInputValidation()

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      await this.onSubmit().catch((error) => {
        if (isApolloError(error)) {
          this.$toasted.global.validationError()
          this.validation.setBackendErrorsFromAppolo(error)
        }
      })
    }
  }
}
</script>
