<template>
  <v-container fluid>
    <FutureIntroductionBlock imageSrc="/feature1.png">
      <template #description> チームのほしいものを管理 </template>
      <template #detail> 説明説明説明 </template>
    </FutureIntroductionBlock>
    <FutureIntroductionBlock imageSrc="/feature1.png" reverse>
      <template #description> お気に入りでサークルチェック </template>
      <template #detail> 説明説明説明 </template>
    </FutureIntroductionBlock>
    <FutureIntroductionBlock imageSrc="/feature1.png">
      <template #description> Excel出力対応 </template>
      <template #detail> 説明説明説明 </template>
    </FutureIntroductionBlock>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'
import FutureIntroductionBlock from '~/components/feature-introduction/FeatureIntroductionBlock.vue'

@Component({
  components: { FutureIntroductionBlock },
})
export default class FeatureIntroductions extends Vue {}
</script>
