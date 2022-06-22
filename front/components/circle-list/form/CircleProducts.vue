<template>
  <v-container>
    <circle-product-row
      v-for="circleProduct in circleProducts"
      :key="circleProduct.id"
      :circle-product="circleProduct"
      v-on="$listeners"
    />
  </v-container>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { PropType } from 'vue'
import { CircleProduct } from '~/apollo/graphql'

@Component({})
export default class CircleProducts extends Vue {
  @Prop({ type: Array as PropType<CircleProduct[]>, required: true })
  private circleProducts!: CircleProduct[]
}
</script>
