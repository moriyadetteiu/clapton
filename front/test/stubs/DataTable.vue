<template>
  <div>
    <span v-for="(item, i) in items" :key="i">
      <span v-for="(header, n) in headers" :key="n">
        {{ header.text }}
        <template v-if="item && header.value && header.value in item">
          {{ item[header.value] }}
        </template>
      </span>
    </span>
  </div>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'

@Component
export default class DataTable extends Vue {
  @Prop({})
  private headers!: any

  @Prop({})
  private items!: any
}
</script>
