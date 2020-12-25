<template>
  <div>aaa</div>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import {
  CirclePlacementClassificationsQuery,
  CirclePlacementClassification,
} from '~/apollo/graphql'

@Component({
  apollo: {
    circlePlacementClassifications: {
      query: CirclePlacementClassificationsQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { teamId }
      },
    },
  },
})
export default class CirclePlacementClassificationPage extends Vue {
  private circlePlacementClassifications!: CirclePlacementClassification[]
}
</script>
