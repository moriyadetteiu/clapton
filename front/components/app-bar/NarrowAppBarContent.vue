<template>
  <v-app-bar-nav-icon @click="openNavigation"></v-app-bar-nav-icon>
</template>

<script lang="ts">
import Component from 'vue-class-component'
import { Vue, Emit } from 'vue-property-decorator'

// HACK: Vueのtemplateはrootに1個しか要素を置けないため、NarrowAppBarNavigationと一緒に使われる想定でコンポーネント分割して使用している
//       また、v-main直下である必要があるので、spanなどでラップする手段は使えない。
@Component({})
export default class NarrowAppBarContent extends Vue {
  @Emit('open-navigation')
  private openNavigation(): void {}
}
</script>
