<template>
  <user-form :user="user" :on-submit="onSubmit" />
</template>

<script lang="ts">
import 'vue-apollo'
import { Vue, Component } from 'nuxt-property-decorator'
import { UserInput, CreateUserMutation } from '~/apollo/graphql'
import UserForm from '~/components/users/UserForm.vue'

@Component({
  head() {
    return {
      title: 'ユーザ登録',
    }
  },
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

  onSubmit(): Promise<void> {
    const res = this.$apollo.mutate({
      mutation: CreateUserMutation,
      variables: {
        input: this.user,
      },
    })

    return res.then(() => {
      this.$toast.success('ユーザを登録しました')

      // TODO: 自動的にログインさせる

      this.$router.push('/login')
    })
  }
}
</script>
