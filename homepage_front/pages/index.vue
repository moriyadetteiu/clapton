<template>
  <div>
    <IndexHeader />
    <FeatureIntroductions />
    <div class="d-flex align-center justify-center mb-3">
      <AppLinkBtn />
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'
import IndexHeader from '~/components/header/IndexHeader.vue'
import FeatureIntroductions from '~/components/feature-introduction/FeatureIntroductions.vue'
import AppLinkBtn from '~/components/button/AppLinkBtn.vue'

@Component({
  components: { IndexHeader, FeatureIntroductions, AppLinkBtn },
})
export default class IndexPage extends Vue {}
</script>
