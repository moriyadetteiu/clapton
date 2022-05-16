<template>
  <v-row>
    <v-col>
      <confirm-dialog v-model="isOpenConfirmDialog" @confirmed="excludeUser" />
      <v-data-table :headers="headers" :items="items" hide-default-footer>
        <template v-slot:item.operations="{ item }">
          <v-btn
            color="delete"
            @click="confirmExcludeUser(item.userAffiliationTeamId)"
          >
            <v-icon left>mdi-account-remove</v-icon>
            除名
          </v-btn>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types'
import {
  Team,
  TeamWithAffiliationUsersQuery,
  ExcludeUserForTeamMutation,
  UserAffiliationTeam,
} from '~/apollo/graphql'

interface AffiliationUserDataTableItems {
  userName: string
  userAffiliationTeamId: string
}

@Component({
  apollo: {
    teamWithAffiliationUsers: {
      query: TeamWithAffiliationUsersQuery,
      variables() {
        const teamId: string = this.$route.params.team_id
        return { id: teamId }
      },
      update(data): Team {
        return data.team
      },
    },
  },
})
export default class AffiliationUsersPage extends Vue {
  private teamWithAffiliationUsers: Team = {
    id: '',
    name: '',
    code: '',
    userAffiliationTeams: [],
  }

  private isOpenConfirmDialog: boolean = false

  private operatingUserAffiliationTeamId: string | null = null

  private readonly headers: DataTableHeader[] = [
    {
      text: '名前',
      value: 'userName',
    },
    {
      text: '操作',
      value: 'operations',
    },
  ]

  private get items(): AffiliationUserDataTableItems[] {
    return this.teamWithAffiliationUsers.userAffiliationTeams.map<
      AffiliationUserDataTableItems
    >((userAffiliationTeam: UserAffiliationTeam) => {
      const user = userAffiliationTeam.user
      return {
        userName: user.name,
        userAffiliationTeamId: userAffiliationTeam.id,
      }
    })
  }

  private confirmExcludeUser(userAffiliationTeamId: string): void {
    this.operatingUserAffiliationTeamId = userAffiliationTeamId
    this.isOpenConfirmDialog = true
  }

  private async excludeUser() {
    const variables = {
      userAffiliationTeamId: this.operatingUserAffiliationTeamId,
    }
    await this.$apollo.mutate({
      mutation: ExcludeUserForTeamMutation,
      variables,
    })
    this.$apollo.queries.teamWithAffiliationUsers.refetch()
    this.isOpenConfirmDialog = false
    this.$toast.success('除名しました')
  }
}
</script>
