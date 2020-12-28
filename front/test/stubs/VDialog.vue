<template>
  <div v-if="value" class="v-dialog"><slot /></div>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'

@Component
export default class VDialog extends Vue {
  @Prop({ type: Boolean, required: true })
  private value!: Boolean
}
</script>
