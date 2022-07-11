<template>
  <v-dialog
    v-model="isOpen"
    v-bind="$attrs"
    :max-width="maxWidth"
    v-on="listeners"
  >
    <v-card>
      <v-card-title class="headline"> {{ message }} </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="onCanceled">キャンセル</v-btn>
        <v-btn color="primary" @click="onConfirmed">実行</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import {
  confirmDialogContainer,
  ConfirmPromiseFn,
} from './ConfirmDialog/ConfirmDialogContainer'

@Component({
  inheritAttrs: false,
})
export default class ConfirmDialog extends Vue {
  @Prop({ type: String, default: '500px' })
  private maxWidth!: String

  private resolve: ConfirmPromiseFn | null = null

  private isOpen: boolean = false

  private customMessage: string | null = null

  private get listeners() {
    return {
      ...this.$listeners,
      input: (event: any) => this.$emit('input', event),
    }
  }

  private get message(): string {
    return this.customMessage ?? '削除します。よろしいですか？'
  }

  private onCanceled(): void {
    if (!this.resolve) {
      return
    }
    this.resolve(false)
    this.close()
  }

  private onConfirmed() {
    if (!this.resolve) {
      return
    }
    this.resolve(true)
    this.close()
  }

  private close() {
    this.isOpen = false
  }

  private promiseListener(resolve: ConfirmPromiseFn, message?: string) {
    this.resolve = resolve
    this.customMessage = message ?? null
    this.isOpen = true
  }

  public created() {
    confirmDialogContainer.listen(this.promiseListener)
  }
}
</script>

<style scoped>
.headline {
  white-space: pre-line;
}
</style>
