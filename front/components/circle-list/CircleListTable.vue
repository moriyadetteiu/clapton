<template>
  <v-data-table
    :headers="headers"
    :items="filteredCircleLists"
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
              :key="filter.getKey()"
              :filter="filter"
              :value="filterConditions[filter.getKey()]"
              @change="(e) => onChangedFilterItem(e, filter)"
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
import FilterItem from './table/filters/FilterItem.vue'
import EventDateFilter from './table/filters/EventDateFilter'
import CirclePlacementClassificationFilter from './table/filters/CirclePlacementClassificationFilter'
import WantPriorityFilter from './table/filters/WantPriorityFilter'
import CircleProductClassificationFilter from './table/filters/CircleProductClassificationFilter'
import {
  FilterConditionItems,
  FilterConditions,
  Filter,
} from './table/filters/filterInterfaces'
import { CircleList } from '~/apollo/graphql'

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

  private filterConditions: FilterConditions = {}

  private get headers(): DataTableHeader[] {
    return this.tableState.getTableHeaders()
  }

  private get filters(): Filter[] {
    return [
      new EventDateFilter(this.filterConditionItems.eventDates),
      new CirclePlacementClassificationFilter(
        this.filterConditionItems.circlePlacementClassifications
      ),
      new WantPriorityFilter(this.filterConditionItems.wantPriorities),
      new CircleProductClassificationFilter(
        this.filterConditionItems.circleProductClassifications
      ),
    ]
  }

  // note: フィルタ後の結果を使いたい & vueのリアクティブの関係でフィルタを扱いづらかったため、自前でフィルタリングを行っている
  private get filteredCircleLists(): CircleList[] {
    return this.circleLists.filter((circleList) => {
      return this.filters.every((filter) => {
        return filter.filter(this.filterConditions, circleList)
      })
    })
  }

  @Emit()
  private openCircleListForm() {}

  private toggleShowFilter(): void {
    this.isShowFilter = !this.isShowFilter
  }

  private onChangedFilterItem(e: string[], filter: Filter) {
    this.filterConditions = {
      ...this.filterConditions,
      [filter.getKey()]: e,
    }
  }

  public created() {
    let filterConditions = {}
    this.filters.forEach((filter) => {
      filterConditions = {
        ...filterConditions,
        [filter.getKey()]: [],
      }
    })
    this.filterConditions = filterConditions
  }
}
</script>
