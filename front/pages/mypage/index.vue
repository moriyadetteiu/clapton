<template>
  <v-container>
    <v-card>
      <v-card-title>プロフィール</v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="4" class="font-weight-bold">お名前（かな）</v-col>
            <v-col cols="8">{{ user.name }}（{{ user.name_kana }}）</v-col>
          </v-row>
          <v-row>
            <v-col cols="4" class="font-weight-bold"
              >ハンドルネーム（かな）</v-col
            >
            <v-col cols="8"
              >{{ user.handle_name }}（{{ user.handle_name_kana }}）</v-col
            >
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
    <v-card class="mt-3">
      <v-card-title>所属チーム</v-card-title>
      <v-card-text>
        <v-container>
          <v-row
            v-for="affiliateTeam in user.affiliateTeams"
            :key="affiliateTeam.id"
          >
            <v-col cols="12"
              ><nuxt-link :to="`/teams/${affiliateTeam.team.id}`">
                {{ affiliateTeam.team.name }}
              </nuxt-link>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'
import { User } from '~/apollo/graphql'
import { userStore } from '~/store'

@Component({})
export default class MyPage extends Vue {
  private get user(): User {
    return userStore.loginUserOrEmptyUser
  }
}
</script>
