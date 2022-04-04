<template>
  <v-form>
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-text-field
            v-model="credential.email"
            outlined
            label="メールアドレス"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="credential.password"
            outlined
            label="パスワード"
            type="password"
          />
        </v-col>
        <v-col cols="12">
          <v-btn color="success" @click="login">ログイン</v-btn>
          <v-btn color="primary" nuxt to="/users/create">ユーザ登録</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { LoginMutation, LoginInput, LoginData } from '~/apollo/graphql'

@Component({})
export default class Login extends Vue {
  private credential: LoginInput = {
    email: '',
    password: '',
  }

  private async login() {
    const response = await this.$apollo.mutate({
      mutation: LoginMutation,
      variables: {
        input: this.credential,
      },
    })

    const loginData: LoginData = response.data.login
    const token = loginData.token

    if (token) {
      this.$toast.success('ログインしました')
      await this.$apolloHelpers.onLogin(token)
      this.$router.push('/')
    }
  }
}
</script>
