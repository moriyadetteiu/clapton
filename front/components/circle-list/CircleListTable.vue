<template>
  <v-data-table
    :headers="headers"
    :items="circleLists"
    height="calc(100vh - 90px)"
    hide-default-footer
    disable-pagination
    fixed-header
    multi-sort
  >
    <template v-slot:top>
      <v-toolbar>
        <v-toolbar-title>サークルリスト</v-toolbar-title>
        <v-spacer />
        <v-btn icon @click="toggleShowFilter"
          ><v-icon>mdi-filter-variant</v-icon></v-btn
        >
        <v-btn color="register" icon @click="openCircleListForm">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>
      <v-expand-transition>
        <v-card v-show="isShowFilter">
          <v-card-text>
            <filter-item
              v-for="filter in filters"
              :key="filter.key"
              v-model="filterConditions[filter.conditionDataKey]"
              :conditions="filter.selections"
            />
          </v-card-text>
        </v-card>
      </v-expand-transition>
    </template>
    <template v-slot:item.circle_product_price="{ item }">
      <template v-if="item.circle_product_price"
        >{{ item.circle_product_price }}円
      </template>
    </template>
    <template v-slot:item.want_circle_product_quantity="{ item }">
      <template v-if="item.want_circle_product_quantity"
        >{{ item.want_circle_product_quantity }}個
      </template>
    </template>
  </v-data-table>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop, Emit } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import TableStateInterface from './table/TableStateInterface'
import {
  CircleList,
  EventDate,
  CirclePlacementClassification,
  WantPriority,
} from '~/apollo/graphql'
import FilterItem, { FilterSelectionItem } from './table/FilterItem.vue'

type filterFunction = (value: any, search: string | null, item: any) => boolean

type Filter = {
  selections: Array<FilterSelectionItem>
  filter: filterFunction
  key: string
  conditionDataKey: string
}

export type FilterConditionItems = {
  eventDates: EventDate[]
  circlePlacementClassifications: CirclePlacementClassification[]
  wantPriorities: WantPriority[]
}

const makeFilter = (
  filterConditionItems: any,
  filterConditions: any,
  key: string,
  conditionDataKey: string
) => {
  return {
    selections: filterConditionItems.eventDates ?? [],
    key,
    conditionDataKey,
    filter: (_: any, __: any, item: CircleList) => {
      return (
        filterConditions.selectedDates.length === 0 ||
        filterConditions.selectedDates.includes(item.event_date_id)
      )
    },
  }
}

@Component({
  components: {
    FilterItem,
  },
})
export default class CircleListTable extends Vue {
  @Prop({
    type: Object as PropType<TableStateInterface>,
    required: true,
  })
  private tableState!: TableStateInterface

  @Prop({
    type: Array as PropType<CircleList[]>,
    required: true,
  })
  circleLists!: CircleList[]

  @Prop({
    type: Object as PropType<FilterConditionItems>,
    required: true,
  })
  private filterConditionItems!: FilterConditionItems

  private isShowFilter: boolean = false

  private filterConditions: { [key: string]: string[] } = {
    selectedDates: [],
    selectedWantPriorities: [],
    selectedCirclePlacementClassifications: [],
  }

  private get headers(): DataTableHeader[] {
    const filters = this.filters
    return this.tableState
      .getTableHeaders()
      .map<DataTableHeader>((header: DataTableHeader) => {
        const foundFilter = filters.find(
          (filter) => (filter.key = header.value)
        )
        if (foundFilter) {
          header.filter = foundFilter.filter
        }
        return header
      })
  }

  private get filters(): Filter[] {
    return [
      makeFilter(
        this.filterConditionItems,
        this.filterConditions,
        'event_date_name',
        'selectedDates'
      ),
    ]
  }

  @Emit()
  private openCircleListForm() {}

  private toggleShowFilter(): void {
    this.isShowFilter = !this.isShowFilter
  }
}
</script>
