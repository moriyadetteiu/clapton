<template>
  <v-row>
    <v-col>
      <v-data-table
        :headers="headers"
        :items="items"
        hide-default-footer
        disable-pagination
      >
        <template v-slot:item.operations="{ item }">
          <v-btn
            color="delete"
            @click="excludeUser(item.userAffiliationTeamId)"
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

  private async excludeUser(userAffiliationTeamId: string) {
    if (!(await this.$confirmDialog.confirm('除名します。よろしいですか？'))) {
      return
    }

    const variables = {
      userAffiliationTeamId,
    }
    await this.$apollo.mutate({
      mutation: ExcludeUserForTeamMutation,
      variables,
    })
    this.$apollo.queries.teamWithAffiliationUsers.refetch()
    this.$toast.success('除名しました')
  }
}
</script>
