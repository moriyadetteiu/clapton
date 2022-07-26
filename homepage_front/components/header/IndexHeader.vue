<template>
  <v-img
    src="/header.jpg"
    :aspect-ratio="4031 / 2827"
    width="100vw"
    height="100vh"
    @load="loadedImage"
  >
    <div class="d-flex align-center justify-center v-image-content">
      <v-fade-transition>
        <div
          v-if="isLoadedImage"
          class="content-wrapper d-flex flex-column align-center"
        >
          <p class="sub-message">Clapton</p>
          <p>コミックマーケット</p>
          <p>みんなで買い物しませんか？</p>
        </div>
      </v-fade-transition>
    </div>
  </v-img>
</template>

<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'

@Component({})
export default class IndexHeader extends Vue {
  private isLoadedImage: boolean = false

  private loadedImage(): void {
    this.isLoadedImage = true
  }
}
</script>

<style scoped>
.v-image-content {
  height: 100%;
  width: 100%;
  background-color: rgba(0, 0, 0, 5%);
}

.content-wrapper {
  color: white;
  font-size: 65px;
  letter-spacing: 3px;
  font-family: serif;
}

.sub-message {
  font-size: 20px;
}
</style>
