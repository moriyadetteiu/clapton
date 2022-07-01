<template>
  <v-data-table
    :headers="headers"
    :items="favoritesWithState"
    height="calc(100vh - 90px)"
    :mobile-breakpoint="0"
    hide-default-footer
    disable-pagination
    fixed-header
    multi-sort
    @click:row="onRowClicked"
    @dblclick:row="onRowDblClicked"
  >
    <template #top>
      <circle-list-table-setting
        v-model="settings"
        :is-open.sync="isOpenSetting"
      />
      <v-toolbar class="elevation-0">
        <v-toolbar-title>お気に入りチェックリスト</v-toolbar-title>
        <v-spacer />
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" @click="openSetting" v-on="on">
              <v-icon>mdi-cog</v-icon>
            </v-btn>
          </template>
          <span>設定</span>
        </v-tooltip>
      </v-toolbar>
    </template>
    <template #[`item.favorite.circle.name`]="{ item }">
      <favorite-state-button :event-id="eventId" :favorite-with-state="item" />
      {{ item.favorite.circle.name }}
    </template>
  </v-data-table>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop, Emit } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import FilterItem from './table/filters/FilterItem.vue'
import CircleListTableSetting, {
  CircleListTableSettings,
} from './table/CircleListTableSetting.vue'
import FavoriteStateButton from '~/components/favorites/FavoriteStateButton.vue'
import { FavoriteWithState } from '~/apollo/graphql'

@Component({
  components: {
    FilterItem,
    CircleListTableSetting,
    FavoriteStateButton,
  },
})
export default class CircleListTable extends Vue {
  @Prop({ type: Array as PropType<FavoriteWithState[]> })
  private favoritesWithState!: FavoriteWithState[]

  @Prop({
    type: String,
    required: true,
  })
  private eventId!: string

  private isOpenSetting: boolean = false

  // HACK: 初期値を指定しているが、子コンポーネントのmountedのタイミングで保存済みの値があれば、v-modelのイベント経由で変更される。
  //       ちょっと複雑な動作をしているため、シンプルな実装にできるのであれば、変更も考えたい
  private settings: CircleListTableSettings = {
    howOpenCircleListForm: 'click',
  }

  private readonly headers: DataTableHeader[] = [
    {
      text: 'サークル名',
      value: 'favorite.circle.name',
    },
  ]

  private openSetting(): void {
    this.isOpenSetting = true
  }

  private onRowClicked(e: any, row: { item: FavoriteWithState }) {
    if (this.settings.howOpenCircleListForm === 'click') {
      this.openCircleListForm(e, row.item)
    }
  }

  private onRowDblClicked(e: any, row: { item: FavoriteWithState }) {
    if (this.settings.howOpenCircleListForm === 'dblclick') {
      this.openCircleListForm(e, row.item)
    }
  }

  @Emit()
  private openCircleListForm(_: any, row: FavoriteWithState) {
    return { circle_id: row.favorite.circle!.id }
  }
}
</script>
