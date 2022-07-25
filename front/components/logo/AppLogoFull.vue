<template>
  <nuxt-link to="/mypage">
    <v-img :src="LogoSrc" width="256" height="80" />
  </nuxt-link>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component({})
export default class AppLogoFull extends Vue {
  private get LogoSrc(): string {
    return `/${this.LogoName}.png`
  }

  private get LogoName(): string {
    return this.$vuetify?.theme?.dark ? 'logo-full' : 'logo-full-light'
  }
}
</script>
