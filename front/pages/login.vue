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
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { LoginMutation, LoginInput } from '~/apollo/graphql'
import UserForm from '~/components/users/UserForm.vue'
// TODO: ~/apollo...を使えるようにする（VSCodeで読み込めるようにする）

@Component({})
export default class Login extends Vue {
  private credential: LoginInput = {
    email: '',
    password: ''
  };

  private async login() {
    const res = await this.$apollo.mutate({
      mutation: LoginMutation,
      variables: {
        input: this.credential,
      }
    })

    const token = res.data.login.token;

    if (token) {
      await this.$apolloHelpers.onLogin(token);
      this.$router.push('/');
    }
  }
}
</script>
