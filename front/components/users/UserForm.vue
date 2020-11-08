<template>
  <v-form>
    <v-container>
      <v-row dense>
        <v-col cols="12">
          <v-text-field v-model="syncedUser.name" label="名前" outlined />
        </v-col>
        <v-col cols="12">
          <v-text-field v-model="syncedUser.name_kana" outlined label="かな" />
        </v-col>
        <v-col cols="12">
          <v-text-field v-model="syncedUser.handle_name" outlined label="ハンドルネーム" />
        </v-col>
        <v-col cols="12">
          <v-text-field v-model="syncedUser.handle_name_kana" outlined label="ハンドルネームのかな" />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="syncedUser.email"
            outlined
            label="メールアドレス"
            hint="ログイン時や、パスワードを忘れた際に使用します。"
            persistent-hint
          />
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="syncedUser.password"
            outlined
            label="パスワード"
            type="password"
          />
        </v-col>
        <v-col cols="12">
          <v-text-field outlined label="パスワード確認" type="password" />
        </v-col>
        <v-col cols="12">
          <slot />
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script lang="ts">
import { Vue, PropSync, Component } from 'nuxt-property-decorator'
import { PropType, PropOptions } from 'vue'
// TODO: ~/apollo...を使えるようにする（VSCodeで読み込めるようにする）
import { UserInput } from '../../apollo/graphql'

@Component
export default class UserForm extends Vue {
  @PropSync('user', { type: Object as PropType<UserInput> } as PropOptions<
    UserInput
  >)
  syncedUser!: UserInput
}
</script>
