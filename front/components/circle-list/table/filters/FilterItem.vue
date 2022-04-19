<template>
  <v-row dense>
    <v-col cols="2">
      <v-subheader>{{ filter.getLabel() }}</v-subheader>
    </v-col>
    <v-col cols="10">
      <v-chip-group v-model="selectedCondition" multiple active-class="primary">
        <v-chip
          v-for="condition in filter.getConditionItems()"
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
import { Vue, Component, Prop, Model } from 'nuxt-property-decorator'
import { Filter } from './filterInterfaces'

@Component({})
export default class FilterItem extends Vue {
  @Prop({
    type: Object as PropType<Filter>,
    required: true,
  })
  private filter!: Filter

  @Model('change', {
    type: Array as PropType<string[]>,
    required: true,
  })
  private value!: Array<string>

  get selectedCondition() {
    return this.value
  }

  set selectedCondition(condition) {
    this.$emit('change', condition)
  }
}
</script>
