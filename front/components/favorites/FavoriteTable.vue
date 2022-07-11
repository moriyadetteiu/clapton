<template>
  <v-data-table
    :headers="headers"
    :items="favorites"
    :mobile-breakpoint="0"
    hide-default-footer
    disable-pagination
    fixed-header
  >
    <template #top>
      <v-card>
        <v-card-text>
          <v-tooltip top>
            <template #activator="{ on, attrs }">
              <v-row v-bind="attrs" v-on="on">
                <v-switch
                  v-model="isEditable"
                  label="編集"
                  dense
                  hide-details
                  class="mt-0 pl-3"
                />
              </v-row>
            </template>
            <span>
              有効にすると、お気に入りの解除が行えるようになります。
            </span>
          </v-tooltip>
        </v-card-text>
      </v-card>
    </template>
    <template #[`item.circle.name`]="{ item }">
      <favorite-button v-if="isEditable" :circle-id="item.circle.id" />
      {{ item.circle.name }}
    </template>
  </v-data-table>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import FavoriteButton from './FavoriteButton.vue'
import { Favorite } from '~/apollo/graphql'

@Component({
  components: {
    FavoriteButton,
  },
})
export default class FavoriteTable extends Vue {
  @Prop({
    type: Array as PropType<Favorite[]>,
    required: true,
  })
  private favorites!: Favorite[]

  private readonly headers: DataTableHeader[] = [
    {
      text: 'サークル名',
      value: 'circle.name',
    },
  ]

  private isEditable: boolean = false
}
</script>
