<template>
  <v-dialog v-model="isOpenSync">
    <v-card>
      <v-card-title> 設定 </v-card-title>
      <v-card-subtitle>※ 端末ごとに保存されます。</v-card-subtitle>
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <v-select
              v-model="settings.howOpenCircleListForm"
              :items="howOpenCircleListFormOptions"
              item-text="text"
              item-value="value"
              label="サークルリスト行の編集方法"
              :persistent-hint="true"
              hint="スマートフォン、ダブレットからアクセスしている場合、ダブルクリックは反応しません。クリックをご使用ください。"
            />
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, PropSync, Component, Watch, Model } from 'nuxt-property-decorator'

type HowOpenCircleListValue = 'click' | 'dblclick'
type HowOpenCircleListOption = {
  value: HowOpenCircleListValue
  text: string
}
export type CircleListTableSettings = {
  howOpenCircleListForm: HowOpenCircleListValue
}

@Component({})
export default class CircleListTableSetting extends Vue {
  @PropSync('isOpen', { type: Boolean, required: true })
  private isOpenSync!: Boolean

  @Model('update:value', {
    type: Object as PropType<CircleListTableSettings>,
    required: true,
  })
  private value!: CircleListTableSettings

  private isMounted: boolean = false

  private get settings(): CircleListTableSettings {
    return this.value
  }

  private set settings(value) {
    this.$emit('update:value', value)
  }

  @Watch('value', { deep: true })
  private onChangeOptions() {
    if (this.isMounted) {
      localStorage.circleListTableOptions = JSON.stringify(this.value)
    }
  }

  private howOpenCircleListFormOptions: HowOpenCircleListOption[] = [
    {
      value: 'click',
      text: 'クリック',
    },
    {
      value: 'dblclick',
      text: 'ダブルクリック',
    },
  ]

  public async mounted() {
    const options = localStorage.getItem('circleListTableOptions')
    if (options) {
      try {
        this.settings = JSON.parse(options)
      } catch (e) {
        localStorage.removeItem('circleListTableOptions')
      }
    }

    // HACK: localStorageから読んだ値でonChangeOptionsが反応しないように描画させた後にisMountedの更新をかけている
    //       そもそもマウント終わったかどうかで分岐させるのもスマートではないので、もっといい方法あれば修正したい。
    await this.$nextTick()
    this.isMounted = true
  }
}
</script>
