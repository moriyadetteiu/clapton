<template>
  <validation-observer ref="validationObserver" tag="v-form">
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-validate-text-field
            v-model="credential.email"
            :validation="validation.getItem('email')"
            outlined
          />
        </v-col>
        <v-col cols="12">
          <v-validate-text-field
            v-model="credential.password"
            :validation="validation.getItem('password')"
            outlined
            type="password"
          />
        </v-col>
        <v-col cols="12">
          <v-btn color="success" @click="login">ログイン</v-btn>
          <v-btn color="primary" nuxt to="/users/create">ユーザ登録</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </validation-observer>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { ValidationObserver } from 'vee-validate'
import { LoginMutation, LoginInput, LoginData, MeQuery } from '~/apollo/graphql'
import { LoginValidation } from '~/validation/loginValidation'
import { userStore } from '~/utils/store-accessor'

@Component({})
export default class Login extends Vue {
  private validation: LoginValidation = new LoginValidation()

  private credential: LoginInput = {
    email: '',
    password: '',
  }

  $refs!: {
    validationObserver: InstanceType<typeof ValidationObserver>
  }

  private async login() {
    const observer = this.$refs.validationObserver
    const isValid = await observer.validate()
    if (!isValid) {
      return
    }

    const response = await this.$apollo.mutate({
      mutation: LoginMutation,
      variables: {
        input: this.credential,
      },
    })

    const loginData: LoginData = response.data.login

    if (loginData.error === 'Unauthorized') {
      this.$toast.error('ログインに失敗しました')
      return
    }

    try {
      const loginUser = await this.fetchLoginUser()
      userStore.setLoginUser(loginUser)
      this.$toast.success('ログインしました')
      this.$router.push('/mypage')
    } catch (e) {
      this.$toast.error('ログインに失敗しました')
      return
    }
  }

  private async fetchLoginUser() {
    return await this.$apollo
      .query({
        query: MeQuery,
      })
      .then((result) => result.data.me)
  }
}
</script>
