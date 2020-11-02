<template>
  <user-form :user="user">
    <v-btn color="success" @click="submit">登録</v-btn>
  </user-form>
</template>

<script lang="ts">
  import 'vue-apollo'
  import { Vue, Component } from "nuxt-property-decorator";
  import UserForm from "~/components/users/UserForm.vue";
  // TODO: ~/apollo...を使えるようにする（VSCodeで読み込めるようにする）
  import { UserInput, CreateUserMutation } from "../../apollo/graphql";

  @Component({
    components: {
      UserForm
    }
  })
  export default class CreateUser extends Vue {
    user: UserInput = {
      name: "",
      email: "",
      password: ""
    };

    submit(): void {
      const res = this.$apollo.mutate({
        mutation: CreateUserMutation,
        variables: {
          input: this.user
        }
      });

      res.then((result: any) => {
        console.log(result);
      }).catch((error: any) => {
        console.log(error);
      })
    }
  };
</script>
