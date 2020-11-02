<template>
  <user-form />
</template>

<script lang="ts">
  import { Vue, Component } from "nuxt-property-decorator";
  import UserForm from "~/components/users/UserForm.vue";

  @Component({
    components: {
      UserForm
    }
  })
  export default class CreateUser extends Vue {
    name: string = 'hello'
  };
</script>
