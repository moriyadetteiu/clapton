<template>
  <v-switch v-model="isDark" label="ダークモード" dense flat v-bind="$attrs" />
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class AppLogo extends Vue {
  // TODO: テストを実行できるように?.にしているのを、テスト側で対応するようにする
  get isDark(): boolean {
    return this?.$vuetify?.theme?.dark || false
  }

  set isDark(dark) {
    if (this?.$vuetify?.theme?.dark !== undefined) {
      this.$vuetify.theme.dark = dark
    }
  }
}
</script>
