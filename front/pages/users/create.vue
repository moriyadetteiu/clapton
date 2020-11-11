<template>
  <user-form :user="user">
    <v-btn color="success" @click="submit">登録</v-btn>
  </user-form>
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { UserInput, CreateUserMutation } from '../../apollo/graphql'
import UserForm from '~/components/users/UserForm.vue'
// TODO: ~/apollo...を使えるようにする（VSCodeで読み込めるようにする）

@Component({
  components: {
    UserForm,
  },
})
export default class CreateUser extends Vue {
  user: UserInput = {
    name: '',
    name_kana: '',
    handle_name: '',
    handle_name_kana: '',
    email: '',
    password: '',
  }

  submit(): void {
    const res = this.$apollo.mutate({
      mutation: CreateUserMutation,
      variables: {
        input: this.user,
      },
    })

    res
      .then(() => {
        // TODO: リダイレクト処理をかける
      })
      .catch(() => {
        // TODO: バリデーション失敗時にはエラーが出るようにする
      })
  }
}
</script>
