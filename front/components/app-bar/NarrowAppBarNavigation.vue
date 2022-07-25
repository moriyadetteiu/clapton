<template>
  <v-navigation-drawer
    v-model="isOpen"
    v-bind="$attrs"
    app
    temporary
    v-on="$listeners"
  >
    <div class="fill-height d-flex flex-column">
      <v-container class="pl-0 pr-0 mb-auto">
        <app-logo-full />
        <template v-if="user !== null">
          <v-expansion-panels flat>
            <v-expansion-panel>
              <v-expansion-panel-header> リスト </v-expansion-panel-header>
              <v-expansion-panel-content>
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
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>

          <v-expansion-panels flat>
            <v-expansion-panel>
              <v-expansion-panel-header> 過去リスト </v-expansion-panel-header>
              <v-expansion-panel-content>
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
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>

          <v-btn text nuxt block to="/favorites"> お気に入り </v-btn>
        </template>
        <template v-if="user !== null">
          <v-btn text block nuxt to="/mypage"> {{ user.name }}さん </v-btn>
          <v-btn text block @click.prevent="logout"> ログアウト </v-btn>
        </template>
        <template v-else>
          <v-btn text block nuxt to="/login"> ログイン </v-btn>
        </template>
      </v-container>
      <v-container>
        <dark-mode-switch hide-details />
      </v-container>
    </div>
  </v-navigation-drawer>
</template>

<script lang="ts">
import Component from 'vue-class-component'
import { Model } from 'vue-property-decorator'
import AbstractAppBarContent from './AbstractAppBarContent.vue'
import AppLogoFull from '~/components/logo/AppLogoFull.vue'
import DarkModeSwitch from '~/components/switch/DarkModeSwitch.vue'

@Component({
  components: {
    AppLogoFull,
    DarkModeSwitch,
  },
})
export default class NarrowAppBarNavigation extends AbstractAppBarContent {
  @Model('input', { required: true, type: Boolean })
  private value!: boolean

  private get isOpen(): boolean {
    return this.value
  }

  private set isOpen(value) {
    this.$emit('update', value)
  }
}
</script>

<style scoped>
.v-expansion-panel {
  background-color: transparent !important;
}

.v-expansion-panel::before {
  box-shadow: none;
}

.v-btn {
  justify-content: left;
  padding-left: 24px !important; /* note: expansion-panel と左側を揃えたいので、24pxを指定 */
  border-radius: 0;
}
</style>
