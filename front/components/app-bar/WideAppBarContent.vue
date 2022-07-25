<template>
  <v-container class="pl-0 pr-0 pt-0 pb-0 align-center">
    <app-logo />
    <v-container class="pl-0 pr-0 d-flex align-center">
      <template v-if="user">
        <v-menu open-on-hover offset-y :max-height="headerMenuMaxHeight">
          <template #activator="{ on, attrs }">
            <v-btn text v-bind="attrs" v-on="on"> リスト </v-btn>
          </template>
          <v-list dense>
            <v-list-item
              v-for="(item, idx) in underwayCircleListItems"
              :key="idx"
              nuxt
              :to="`/teams/${item.team.id}/events/${item.event.id}/circle-list`"
            >
              {{ item.team.name }}
              （ {{ item.event.name }} ）
            </v-list-item>
          </v-list>
        </v-menu>
        <v-menu open-on-hover offset-y :max-height="headerMenuMaxHeight">
          <template #activator="{ on, attrs }">
            <v-btn text v-bind="attrs" v-on="on"> 過去リスト </v-btn>
          </template>
          <v-list dense>
            <v-list-item
              v-for="(item, idx) in finishedCircleListItems"
              :key="idx"
              nuxt
              :to="`/teams/${item.team.id}/events/${item.event.id}/circle-list`"
            >
              {{ item.team.name }}
              （ {{ item.event.name }} ）
            </v-list-item>
          </v-list>
        </v-menu>
        <v-btn text nuxt to="/favorites">お気に入り</v-btn>
        <v-spacer />
        <v-btn text @click.prevent="logout">ログアウト</v-btn>
      </template>
      <template v-else>
        <v-spacer />
        <v-btn text nuxt to="/login">ログイン</v-btn>
      </template>
    </v-container>
  </v-container>
</template>

<script lang="ts">
import Component from 'vue-class-component'
import AbstractAppBarContent from './AbstractAppBarContent.vue'
import AppLogo from '~/components/logo/AppLogo.vue'

@Component({
  components: { AppLogo },
})
export default class WideAppBarContent extends AbstractAppBarContent {
  private get headerMenuMaxHeight(): string {
    return 'calc(100vh - 100px)'
  }
}
</script>
