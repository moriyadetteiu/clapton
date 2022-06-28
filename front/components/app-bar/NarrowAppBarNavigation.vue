<template>
  <v-navigation-drawer
    v-model="isOpen"
    v-bind="$attrs"
    v-on="$listeners"
    app
    temporary
  >
    <template v-if="user !== null">
      <v-expansion-panels class="rounded-0">
        <v-expansion-panel class="bg-transparent">
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

      <v-expansion-panels flat class="rounded-0">
        <v-expansion-panel class="bg-transparent">
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

      <v-btn text nuxt block to="/favorites" class="rounded-0">
        お気に入り
      </v-btn>
    </template>
    <v-spacer />
    <template v-if="user !== null">
      <v-btn text block nuxt to="/mypage" class="rounded-0">
        {{ user.name }}さん
      </v-btn>
      <v-btn text block @click.prevent="logout" class="rounded-0">
        ログアウト
      </v-btn>
    </template>
    <template v-else>
      <v-btn text block nuxt to="/login" class="rounded-0"> ログイン </v-btn>
    </template>
  </v-navigation-drawer>
</template>

<script lang="ts">
import Component from 'vue-class-component'
import { Model } from 'vue-property-decorator'
import AbstractAppBarContent from './AbstractAppBarContent.vue'

@Component({})
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
.bg-transparent {
  background-color: transparent !important;
}

.v-expansion-panel::before {
  box-shadow: none;
}

/* stylelint-disable-next-line selector-class-pattern */
.v-btn {
  justify-content: left;
  padding-left: 24px !important;
}
</style>
