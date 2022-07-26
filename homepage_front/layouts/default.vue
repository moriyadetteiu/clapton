<template>
  <v-app dark>
    <v-app-bar fixed app> header </v-app-bar>
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>
    <v-footer app>
      <span> footer </span>
    </v-footer>
  </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'

@Component({})
export default class DefaultLayout extends Vue {}
</script>
