<template>
  <validation-observer
    ref="validationObserver"
    tag="form"
    @submit.prevent="submit"
  >
    <v-container>
      <v-row dense>
        <v-col cols="12"> パスワードリセットメール送信 </v-col>
      </v-row>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="email"
            :validation="validation.getItem('email')"
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
import { ForgetPasswordMutation } from '~/apollo/graphql'
import { ForgetPasswordInputValidation } from '~/validation/validations'

@Component({
  head() {
    return {
      title: 'パスワード再発行',
    }
  },
})
export default class ForgetPasswordPage extends Vue {
  private email: string = ''

  private validation: ForgetPasswordInputValidation =
    new ForgetPasswordInputValidation()

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async submit(): Promise<void> {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (isValid) {
      const res = await this.$apollo.mutate({
        mutation: ForgetPasswordMutation,
        variables: {
          email: this.email,
        },
      })

      if (res.data.error) {
        this.$toast.error(res.data.error)
      } else {
        this.$toast.success('パスワードリセットのメールを送信しました。')
        this.$router.push('/login')
      }
    }
  }
}
</script>
