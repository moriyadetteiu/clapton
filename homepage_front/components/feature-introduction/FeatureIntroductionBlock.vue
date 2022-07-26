<template>
  <v-row :class="reverse ? `flex-row-reverse` : ``">
    <v-col cols="6">
      <v-img :src="imageSrc" />
    </v-col>
    <v-col cols="6">
      <div class="description">
        <slot name="description" />
      </div>
      <div class="detail">
        <slot name="detail" />
      </div>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'nuxt-property-decorator'

@Component({})
export default class FeatureIntroductionBlock extends Vue {
  @Prop({ type: String, required: true })
  private imageSrc: string

  @Prop({ type: Boolean, default: false })
  private reverse: boolean
}
</script>

<style scoped>
.description {
  font-size: 40px;
}

.detail {
  font-size: 20px;
}
</style>
