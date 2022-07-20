<template>
  <v-container>
    <v-row dense>
      <v-col>
        <v-textarea v-model="input.memo" placeholder="メモ" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component } from 'nuxt-property-decorator'
import AbstractFormInput from '~/components/form/AbstractFormInput.vue'

export type CareAboutCircleMemoInput = {
  memo: string
}

@Component({})
export default class CareAboutCircleFormInput extends AbstractFormInput<
  CareAboutCircleMemoInput,
  null
> {}
</script>
