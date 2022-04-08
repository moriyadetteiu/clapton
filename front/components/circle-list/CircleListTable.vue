<template>
  <v-data-table
    class="mt-5"
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
        <v-btn color="register" @click="openCircleListForm"
          ><v-icon>mdi-plus</v-icon>追加</v-btn
        >
      </v-toolbar>
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
import { CircleList } from '~/apollo/graphql'

@Component({})
export default class CircleListTable extends Vue {
  private readonly headers: DataTableHeader[] = [
    {
      text: '日付',
      value: 'event_date_name',
      width: '75px',
    },
    {
      text: '区分',
      value: 'circle_placement_classification_name',
      width: '105px',
    },
    {
      text: '配置',
      value: 'placement_full',
      width: '90px',
    },
    {
      text: '頒布物分類',
      value: 'circle_product_classification_name',
      width: '110px',
    },
    {
      text: '頒布物名',
      value: 'circle_product_name',
      width: '250px',
    },
    {
      text: '値段',
      value: 'circle_product_price',
      width: '80px',
    },
    {
      text: '個数',
      value: 'want_circle_product_quantity',
      width: '75px',
    },
    {
      text: '優先度',
      value: 'want_priority_name',
      width: '90px',
    },
    {
      text: '購入者名',
      value: 'user_name',
      width: '105px',
    },
    {
      text: '備考',
      value: 'circle_memo',
      width: '300px',
    },
  ]

  @Prop({
    type: Array as PropType<CircleList[]>,
    required: true,
  })
  circleLists!: CircleList[]

  @Emit()
  private openCircleListForm() {}
}
</script>
