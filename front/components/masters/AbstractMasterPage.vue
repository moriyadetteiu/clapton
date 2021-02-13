<template>
  <v-row>
    <v-col>
      <component
        :is="formComponentName"
        :is-open.sync="isOpenFormDialog"
        :model="selectedModel"
        :team-id="teamId"
        @saved="refresh"
      />
      <confirm-dialog v-model="isOpenConfirmDialog" @confirmed="remove" />
      <v-data-table
        :headers="headers"
        :items="models"
        hide-default-footer
        disable-pagination
      >
        <template v-slot:top>
          <v-toolbar>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer />
            <v-btn color="register" @click="create"
              ><v-icon>mdi-plus</v-icon>追加</v-btn
            >
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn color="edit" @click="edit(item)"
            ><v-icon left>mdi-pencil</v-icon>編集</v-btn
          >
          <v-btn color="delete" @click="confirmRemove(item)"
            ><v-icon left>mdi-delete</v-icon>削除</v-btn
          >
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { DocumentNode } from 'graphql'
import { Vue, Component } from 'nuxt-property-decorator'
import { DataTableHeader } from 'vuetify/types/index'
import ConfirmDialog from '~/components/dialog/ConfirmDialog.vue'

// @ts-ignore
@Component({
  components: { ConfirmDialog },
})
export default abstract class AbstractMasterPage<
  Model extends { id: string }
> extends Vue {
  protected abstract readonly defaultSelectedModel: Model
  protected abstract readonly headers: DataTableHeader[]
  protected abstract readonly deleteMutation: DocumentNode
  protected abstract readonly formComponentName: string
  protected abstract readonly title: string

  protected models: Model[] = []

  private isOpenFormDialog: boolean = false
  private isOpenConfirmDialog: boolean = false
  private selectedModel?: Model

  private setDefaultSelectedModel(): void {
    this.selectedModel = this.defaultSelectedModel
  }

  private create(): void {
    this.setDefaultSelectedModel()
    this.isOpenFormDialog = true
  }

  private edit(model: Model): void {
    this.selectedModel = model
    this.isOpenFormDialog = true
  }

  private confirmRemove(model: Model): void {
    this.selectedModel = model
    this.isOpenConfirmDialog = true
  }

  private remove(): void {
    const res = this.$apollo.mutate({
      mutation: this.deleteMutation,
      variables: {
        id: this.selectedModel?.id,
      },
    })

    res
      .then(() => {
        this.$toast.success('削除しました')
        this.isOpenConfirmDialog = false
        this.refresh()
      })
      .catch(() => {
        this.$toast.error('削除に失敗しました')
      })
  }

  private refresh(): void {
    this.$apollo.queries.models.refetch()
  }

  private created(): void {
    this.setDefaultSelectedModel()
  }

  private get teamId(): string {
    return this.$route.params.team_id
  }
}
</script>
