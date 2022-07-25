<template>
  <v-container>
    <v-app-bar-nav-icon @click="openNavigation" />
    <app-logo />
  </v-container>
</template>

<script lang="ts">
import Component from 'vue-class-component'
import { Vue, Emit } from 'vue-property-decorator'
import AppLogo from '~/components/logo/AppLogo.vue'

// HACK: Vueのtemplateはrootに1個しか要素を置けないため、NarrowAppBarNavigationと一緒に使われる想定でコンポーネント分割して使用している
//       また、v-main直下である必要があるので、spanなどでラップする手段は使えない。
@Component({
  components: { AppLogo },
})
export default class NarrowAppBarContent extends Vue {
  @Emit('open-navigation')
  private openNavigation(): void {}
}
</script>
