<template>
  <v-app dark>
    <v-app-bar fixed absolute color="transparent" elevation="0">
      <v-container class="ml-0 mt-10">
        <AppLogoFull />
      </v-container>
    </v-app-bar>
    <v-main>
      <Nuxt />
    </v-main>
    <v-footer>
      <v-container class="ml-0">
        <AppLogoFull black />
      </v-container>
    </v-footer>
  </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'
import AppLogoFull from '~/components/logo/AppLogoFull.vue'

@Component({
  components: {
    AppLogoFull,
  },
})
export default class DefaultLayout extends Vue {}
</script>
