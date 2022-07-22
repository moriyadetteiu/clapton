<template>
  <validation-observer
    ref="validationObserver"
    tag="form"
    @submit.prevent="submit"
  >
    <v-container>
      <v-row dense>
        <v-col cols="12"> パスワード再設定 </v-col>
      </v-row>
      <v-row dense>
        <v-col cols="12"> メールアドレス: {{ email }} </v-col>
      </v-row>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="password"
            outlined
            :validation="validation.getItem('password')"
            type="password"
            vid="password"
          />
        </v-col>
      </v-row>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="confirmationPassword"
            outlined
            label="パスワード確認"
            rules="required|password:@password"
            type="password"
          />
        </v-col>
      </v-row>
      <v-row dense>
        <v-col cols="12">
          <submit-btn> 送信 </submit-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { ValidationObserver } from 'vee-validate'
import { ResetPasswordMutation } from '~/apollo/graphql'
import { ResetPasswordInputValidation } from '~/validation/validations'

@Component({
  head() {
    return {
      title: 'パスワード再発行',
    }
  },
})
export default class ResetPasswordPage extends Vue {
  private validation: ResetPasswordInputValidation =
    new ResetPasswordInputValidation()

  private password: string = ''
  private confirmationPassword: string = ''

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private get email(): string {
    return this.$route.query.email as string
  }

  private async submit(): Promise<void> {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const variables = {
        input: {
          password: this.password,
          password_confirmation: this.confirmationPassword,
          token: this.$route.query.token,
          email: this.email,
        },
      }

      const res = await this.$apollo.mutate({
        mutation: ResetPasswordMutation,
        variables,
      })

      const error = res.data?.resetPassword?.error ?? null
      if (error) {
        this.$toast.error(error)
      } else {
        this.$toast.success('パスワードを設定しました。')
        this.$router.push('/login')
      }
    }
  }
}
</script>
