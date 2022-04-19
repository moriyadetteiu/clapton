<template>
  <v-row dense>
    <v-col cols="2">
      <v-subheader>日付</v-subheader>
    </v-col>
    <v-col cols="10">
      <v-chip-group multiple active-class="primary" v-model="selectedCondition">
        <v-chip
          v-for="condition in conditions"
          :key="condition.id"
          :value="condition.id"
        >
          {{ condition.name }}
        </v-chip>
      </v-chip-group>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop, Emit, Model } from 'nuxt-property-decorator'

export interface FilterSelectionItem {
  id: string
  name: string
}

@Component({})
export default class FilterItem extends Vue {
  @Prop({
    type: Array as PropType<FilterSelectionItem[]>,
    required: true,
  })
  private conditions!: FilterSelectionItem[]

  @Model('change', {
    type: Array,
    required: true,
  })
  private value!: Array<any>

  get selectedCondition() {
    return this.value
  }

  set selectedCondition(condition) {
    this.$emit('change', condition)
  }
}
</script>
