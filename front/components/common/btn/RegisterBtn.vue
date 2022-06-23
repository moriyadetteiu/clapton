<template>
  <v-tooltip top>
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="register"
        icon
        v-bind="{ ...$attrs, ...attrs }"
        v-on="{ ...on, ...listeners }"
      >
        <v-icon>mdi-plus</v-icon>
      </v-btn>
    </template>
    <span>追加</span>
  </v-tooltip>
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
