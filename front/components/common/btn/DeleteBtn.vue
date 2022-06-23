<template>
  <v-tooltip top>
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="delete"
        icon
        v-bind="{ ...attrs, ...$attrs }"
        v-on="{ ...on, ...listeners }"
      >
        <v-icon>mdi-delete</v-icon>
      </v-btn>
    </template>
    <span>削除</span>
  </v-tooltip>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'

@Component({
  inheritAttrs: false,
})
export default class DeleteBtn extends Vue {
  @Prop({ type: Boolean, default: true })
  private confirm!: boolean

  @Prop({ type: String })
  private confirmMessage!: string

  private get listeners() {
    return {
      ...this.$listeners,
      click: this.onClicked,
    }
  }

  private async onClicked(event: any) {
    if (
      this.confirm &&
      !(await this.$confirmDialog.confirm(this.confirmMessage))
    ) {
      return
    }

    this.$emit('click', event)
  }
}
</script>
