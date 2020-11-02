<template>
  <v-form>
    <v-container>
      <v-row>
        <v-col cols="12">
          <v-text-field outlined label="名前"/>
        </v-col>
        <v-col cols="12">
          <v-text-field outlined label="ニックネーム"/>
        </v-col>
        <v-col cols="12">
          <v-text-field
            outlined
            label="メールアドレス"
            hint="ログイン時や、パスワードを忘れた際に使用します。"
            persistent-hint
          />
        </v-col>
        <v-col cols="12">
          <v-text-field outlined label="パスワード" type="password"/>
        </v-col>
        <v-col cols="12">
          <v-text-field outlined label="パスワード確認" type="password"/>
        </v-col>
        <v-col cols="12">
          <slot />
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script lang="ts">
  import { Vue, PropSync, Component } from "nuxt-property-decorator";

  @Component
  export default class CreateUser extends Vue {
  };
</script>
