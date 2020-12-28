<template>
  <v-dialog v-on="listeners" v-bind="$attrs" v-model="isOpen">
    <v-card>
      <v-card-title class="headline">削除します。よろしいですか？</v-card-title>
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
import { PropType, PropOptions } from 'vue'
import { EventInput } from '~/apollo/graphql'

@Component({
  inheritAttrs: false,
})
export default class ConfirmDialog extends Vue {
  @Prop({ type: Boolean, required: true })
  private value!: Boolean

  private get isOpen(): Boolean {
    return this.value
  }

  private set isOpen(isOpen: Boolean) {
    this.$emit('input', isOpen)
  }

  private get listeners() {
    return {
      ...this.$listeners,
      input: (event: any) => this.$emit('input', event),
    }
  }

  private onCanceled() {
    this.isOpen = false
  }

  private onConfirmed() {
    this.$emit('confirmed')
  }
}
</script>
