<template>
  <v-img :src="imageSrc" width="256" height="80" />
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'nuxt-property-decorator'

@Component({})
export default class AppLogoFull extends Vue {
  @Prop({ type: Boolean, default: false })
  private black: boolean

  private get imageName(): string {
    return this.black ? 'logo-full-light' : 'logo-full'
  }

  private get imageSrc(): string {
    return `/${this.imageName}.png`
  }
}
</script>
