<template>
  <nuxt-link to="/mypage">
    <v-img :src="LogoSrc" width="48" height="48" />
  </nuxt-link>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component({})
export default class AppLogo extends Vue {
  private get LogoSrc(): string {
    return `/${this.LogoName}.png`
  }

  private get LogoName(): string {
    return this.$vuetify?.theme?.dark ? 'logo' : 'logo-light'
  }
}
</script>
