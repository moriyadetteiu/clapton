<template>
  <v-btn color="register" icon v-bind="$attrs" v-on="listeners">
    <v-icon>mdi-plus</v-icon>
  </v-btn>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class RegisterBtn extends Vue {
  private get listeners() {
    return {
      ...this.$listeners,
      click: (event: any) => this.$emit('click', event),
    }
  }
}
</script>
