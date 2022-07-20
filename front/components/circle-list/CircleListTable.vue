<template>
  <v-data-table
    :headers="headers"
    :items="filteredCircleLists"
    height="calc(100vh - 90px)"
    :mobile-breakpoint="0"
    hide-default-footer
    disable-pagination
    fixed-header
    multi-sort
    :show-select="isEnablePriceSimulation"
    @click:row="onRowClicked"
    @dblclick:row="onRowDblClicked"
    @current-items="onUpdateTableCurrentItems"
    @input="onItemSelected"
  >
    <template #top>
      <v-toolbar class="elevation-0">
        <export-circle-list
          :is-open.sync="isOpenExportCircleList"
          :table-state="tableState"
          :circle-list-ids="shownTableCircleListItemIds"
        />
        <circle-list-table-setting
          v-model="settings"
          :is-open.sync="isOpenSetting"
        />
        <price-breakdown
          :circle-lists="calculateTargetCircleLists"
          :is-open.sync="isOpenPriceBreakdown"
        />
        <v-toolbar-title>サークルリスト</v-toolbar-title>
        <v-spacer />
        <register-btn @click="openCircleListForm" />
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" @click="toggleShowFilter" v-on="on">
              <v-icon>mdi-filter-variant</v-icon>
            </v-btn>
          </template>
          <span>フィルター</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" @click="openExportCircleList" v-on="on">
              <v-icon>mdi-file-download</v-icon>
            </v-btn>
          </template>
          <span>Excelダウンロード</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" @click="openSetting" v-on="on">
              <v-icon>mdi-cog</v-icon>
            </v-btn>
          </template>
          <span>設定</span>
        </v-tooltip>
      </v-toolbar>
      <v-expand-transition>
        <v-card v-show="isShowFilter" tile class="elevation-0">
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
      <template v-if="item.circle_product_price !== null"
        >{{ item.circle_product_price }}円
      </template>
    </template>
    <template #[`item.want_circle_product_quantity`]="{ item }">
      <template v-if="item.want_circle_product_quantity"
        >{{ item.want_circle_product_quantity }}個
      </template>
    </template>
    <template #[`item.memo`]="{ item }">
      <div class="memo">{{ item.memo }}</div>
    </template>
    <template #[`body.append`]>
      <tr>
        <td :colspan="headers.length">
          合計金額: {{ totalPrice }}円

          <v-tooltip top>
            <template #activator="{ on, attrs }">
              <v-btn icon v-bind="attrs" v-on="on" @click="openPriceBreakdown">
                <v-icon> mdi-text-box-search-outline </v-icon>
              </v-btn>
            </template>
            <span> 内訳をみる </span>
          </v-tooltip>
          <v-tooltip top>
            <template #activator="{ on, attrs }">
              <v-btn
                icon
                v-bind="attrs"
                v-on="on"
                @click="togglePriceSimulation"
              >
                <v-icon> mdi-text-box-check-outline </v-icon>
              </v-btn>
            </template>
            <span>
              <template v-if="isEnablePriceSimulation">
                金額シミュレーションをやめる
              </template>
              <template v-else> 金額シミュレーションする </template>
              <br />
              チェックがついたもののみ計算対象になります
            </span>
          </v-tooltip>
        </td>
      </tr>
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
import CircleListTableSetting, {
  CircleListTableSettings,
} from './table/CircleListTableSetting.vue'
import PriceBreakdown from './table/PriceBreakdown.vue'
import { CircleList } from '~/apollo/graphql'
import FavoriteButton from '~/components/favorites/FavoriteButton.vue'

@Component({
  components: {
    FilterItem,
    FavoriteButton,
    ExportCircleList,
    CircleListTableSetting,
    PriceBreakdown,
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

  private isOpenSetting: boolean = false

  private isOpenPriceBreakdown: boolean = false

  private isEnablePriceSimulation: boolean = false

  private selectedItems: CircleList[] = []

  // HACK: 初期値を指定しているが、子コンポーネントのmountedのタイミングで保存済みの値があれば、v-modelのイベント経由で変更される。
  //       ちょっと複雑な動作をしているため、シンプルな実装にできるのであれば、変更も考えたい
  private settings: CircleListTableSettings = {
    howOpenCircleListForm: 'click',
  }

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

  private get calculateTargetCircleLists(): CircleList[] {
    return this.isEnablePriceSimulation
      ? this.selectedItems
      : this.filteredCircleLists
  }

  private get totalPrice(): number {
    return this.calculateTargetCircleLists.reduce((prev, current) => {
      const currentPerPrice = Number(current.circle_product_price ?? 0)
      const currentQuantity = Number(current.want_circle_product_quantity ?? 0)
      const currentPrice = currentPerPrice * currentQuantity

      return prev + currentPrice
    }, 0)
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

  private openSetting(): void {
    this.isOpenSetting = true
  }

  private openPriceBreakdown(): void {
    this.isOpenPriceBreakdown = true
  }

  private togglePriceSimulation(): void {
    this.isEnablePriceSimulation = !this.isEnablePriceSimulation
  }

  private onRowClicked(e: any, row: { item: CircleList }) {
    if (this.settings.howOpenCircleListForm === 'click') {
      this.openCircleListForm(e, row)
    }
  }

  private onRowDblClicked(e: any, row: { item: CircleList }) {
    if (this.settings.howOpenCircleListForm === 'dblclick') {
      this.openCircleListForm(e, row)
    }
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

  private onItemSelected(selectedItems: CircleList[]): void {
    this.selectedItems = selectedItems
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

<style scoped>
.memo {
  white-space: pre-line;
}
</style>
