<template>
  <v-tooltip top>
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="edit"
        icon
        v-bind="{ ...attrs, ...$attrs }"
        v-on="{ ...on, ...listeners }"
      >
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
    </template>
    <span>編集</span>
  </v-tooltip>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class EditBtn extends Vue {
  private get listeners() {
    return {
      ...this.$listeners,
      click: (event: any) => this.$emit('click', event),
    }
  }
}
</script>
