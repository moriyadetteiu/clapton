<template>
  <v-overlay absolute :value="isLoading">
    <v-progress-circular indeterminate color="primary" />
  </v-overlay>
</template>

<script lang="ts">
import Component from 'vue-class-component'
import { Prop, Vue } from 'vue-property-decorator'

@Component({})
export default class AppLoading extends Vue {
  @Prop({ required: true, type: Boolean })
  private isLoading!: boolean
}
</script>
