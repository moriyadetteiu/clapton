<template>
  <user-form :user="user" @submit="submit" />
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { UserInput, CreateUserMutation } from '~/apollo/graphql'
import UserForm from '~/components/users/UserForm.vue'

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
        this.$toast.success('ユーザを登録しました')

        // TODO: 自動的にログインさせる

        this.$router.push('/')
      })
      .catch(() => {
        this.$toasted.global.validationError()
        // TODO: バリデーション失敗時にはエラーが出るようにする
      })
  }
}
</script>
