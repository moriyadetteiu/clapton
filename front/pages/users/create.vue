<template>
  <user-form>
    <v-btn color="success">登録</v-btn>
  </user-form>
</template>

<script lang="ts">
  import { Vue, Component } from "nuxt-property-decorator";
  import UserForm from "~/components/users/UserForm.vue";
  // TODO: ~/apollo...を使えるようにする（VSCodeで読み込めるようにする）
  import { User } from "../../apollo/graphql";

@Component({
    components: {
      UserForm
    }
  })
  export default class CreateUser extends Vue {
  };
</script>
