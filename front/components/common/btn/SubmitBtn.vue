<template>
  <v-btn
    color="success"
    type="submit"
    v-bind="{ ...$attrs }"
    v-on="{ ...$listeners }"
  >
    <slot />
  </v-btn>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class SubmitBtn extends Vue {}
</script>
