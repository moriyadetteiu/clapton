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
import TableStateInterface from './table/TableStateInterface'

@Component({})
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

  private get headers(): DataTableHeader[] {
    return this.tableState.getTableHeaders()
  }

  @Emit()
  private openCircleListForm() {}
}
</script>
