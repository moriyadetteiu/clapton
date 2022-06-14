<template>
  <v-data-table
    :headers="headers"
    :items="filteredCircleLists"
    height="calc(100vh - 90px)"
    hide-default-footer
    disable-pagination
    fixed-header
    multi-sort
    @dblclick:row="openCircleListForm"
    @current-items="onUpdateTableCurrentItems"
  >
    <template #top>
      <v-toolbar>
        <export-circle-list
          :is-open.sync="isOpenExportCircleList"
          :table-state="tableState"
          :circle-list-ids="shownTableCircleListItemIds"
        />
        <v-toolbar-title>サークルリスト</v-toolbar-title>
        <v-spacer />
        <register-btn @click="openCircleListForm" />
        <v-btn icon @click="toggleShowFilter"
          ><v-icon>mdi-filter-variant</v-icon></v-btn
        >
        <v-btn icon @click="openExportCircleList">
          <v-icon>mdi-file-download</v-icon>
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
    <template #[`item.circle_name`]="{ item }">
      <favorite-button :circle-id="item.circle_id" />
      {{ item.circle_name }}
    </template>
    <template #[`item.circle_product_price`]="{ item }">
      <template v-if="item.circle_product_price"
        >{{ item.circle_product_price }}円
      </template>
    </template>
    <template #[`item.want_circle_product_quantity`]="{ item }">
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
import EventDateFilter from './table/filters/EventDateFilter'
import CirclePlacementClassificationFilter from './table/filters/CirclePlacementClassificationFilter'
import WantPriorityFilter from './table/filters/WantPriorityFilter'
import CircleProductClassificationFilter from './table/filters/CircleProductClassificationFilter'
import {
  FilterConditionItems,
  FilterConditions,
  Filter,
} from './table/filters/filterInterfaces'
import ExportCircleList from './table/ExportCircleList.vue'
import FilterItem from './table/filters/FilterItem.vue'
import { CircleList } from '~/apollo/graphql'
import FavoriteButton from '~/components/favorites/FavoriteButton.vue'

@Component({
  components: {
    FilterItem,
    FavoriteButton,
    ExportCircleList,
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

  private isOpenExportCircleList: boolean = false

  private filterConditions: FilterConditions = {}

  private shownTableCircleListItemIds: string[] = []

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
  private openCircleListForm(_: any, row?: { item: CircleList }) {
    const circleList = row?.item || null
    return circleList
  }

  private toggleShowFilter(): void {
    this.isShowFilter = !this.isShowFilter
  }

  private openExportCircleList(): void {
    this.isOpenExportCircleList = true
  }

  private onChangedFilterItem(e: string[], filter: Filter) {
    this.filterConditions = {
      ...this.filterConditions,
      [filter.getKey()]: e,
    }
  }

  private onUpdateTableCurrentItems(items: CircleList[]): void {
    this.shownTableCircleListItemIds = items.map((item) => item.id)
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
