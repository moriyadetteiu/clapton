<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title>合計金額内訳</v-card-title>
      <v-card-text>
        <v-expansion-panels>
          <v-expansion-panel
            v-for="groupedPrices in groupedPrices"
            :key="groupedPrices.label"
          >
            <v-expansion-panel-header>{{
              groupedPrices.label
            }}</v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-data-table
                :headers="headers"
                :items="groupedPrices.prices"
                :mobile-breakpoint="0"
                hide-default-footer
                disable-pagination
              >
                <template #[`item.value`]="{ item }">
                  {{ item.value }}円
                </template>
                <template #[`item.quantity`]="{ item }">
                  {{ item.quantity }}個
                </template>
              </v-data-table>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Prop, PropSync, Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import { CircleList } from '~/apollo/graphql'

type GroupHeader = {
  label: string
  text: keyof CircleList
  value: keyof CircleList
}

type GroupPriceItem = {
  id: string
  text: string
  value: number
  quantity: number
}

@Component({})
export default class PriceBreakdown extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Prop({ type: Array as PropType<CircleList[]>, required: true })
  private readonly circleLists!: CircleList[]

  private readonly headers: DataTableHeader[] = [
    {
      text: '分類',
      value: 'text',
    },
    {
      text: '金額',
      value: 'value',
    },
    {
      text: '数量',
      value: 'quantity',
    },
  ]

  private readonly groupHeaders: GroupHeader[] = [
    {
      label: '配置分類別',
      text: 'circle_placement_classification_name',
      value: 'circle_placement_classification_id',
    },
    {
      label: '頒布物分類別',
      text: 'circle_product_classification_name',
      value: 'circle_product_classification_id',
    },
    {
      label: '優先度別',
      text: 'want_priority_name',
      value: 'want_priority_id',
    },
  ]

  private get groupedPrices(): any {
    return this.groupHeaders.map((groupHeader) => {
      const prices = this.circleLists.reduce(
        (prev: GroupPriceItem[], current) => {
          const currentValue: string | null =
            (current[groupHeader.value] as string) ?? null

          if (!currentValue) {
            return prev
          }

          const prices: GroupPriceItem[] = [...prev]
          let priceIndex = prices.findIndex(
            (price) => price.id === currentValue
          )
          if (priceIndex === -1) {
            prices.push({
              id: currentValue,
              text: current[groupHeader.text] as string,
              value: 0,
              quantity: 0,
            })
            priceIndex = prices.length - 1
          }

          prices[priceIndex].value +=
            (current['circle_product_price'] ?? 0) *
            (current['want_circle_product_quantity'] ?? 0)
          prices[priceIndex].quantity++

          return prices
        },
        []
      )

      return {
        label: groupHeader.label,
        prices,
      }
    })
  }
}
</script>
