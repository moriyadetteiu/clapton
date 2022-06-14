<template>
  <v-row>
    <v-col cols="12" class="text--primary pb-0 d-flex">
      {{ circleProduct.circleProductClassification.name }}
      {{ circleProduct.name }}
      <v-spacer />
      <template v-if="hasMyWantCircleProduct">
        <edit-btn @click="editCircleProduct" />
        <delete-btn @click="deleteCircleProduct" />
      </template>
    </v-col>
    <v-col cols="12" class="pt-0">
      購入者:
      <v-chip
        v-for="wantCircleProduct in circleProduct.wantCircleProducts"
        :key="wantCircleProduct.id"
        small
        class="mr-2"
        :color="
          wantCircleProduct.careAboutCircle.joinEvent.user.id === me.id
            ? `primary`
            : ``
        "
      >
        {{ wantCircleProduct.careAboutCircle.joinEvent.user.name }}
      </v-chip>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { PropType } from 'vue'
import { Vue, Prop, Component, Emit } from 'nuxt-property-decorator'
import {
  CircleProduct,
  DeleteCircleProductMutation,
  User,
  WantCircleProduct,
} from '~/apollo/graphql'
import { userStore } from '~/store'

@Component({})
export default class CircleListRow extends Vue {
  @Prop({ type: Object as PropType<CircleProduct>, required: true })
  private circleProduct!: CircleProduct

  @Emit('edit-circle-product')
  private editCircleProduct(): CircleProduct {
    return this.circleProduct
  }

  @Emit('delete-circle-product')
  private onDeleteCircleProduct(): void {}

  private get me(): User {
    return userStore.loginUserOrEmptyUser
  }

  private get myWantCircleProduct(): WantCircleProduct | null {
    return (
      this.circleProduct.wantCircleProducts.find(
        (wantCircleProduct) =>
          wantCircleProduct?.careAboutCircle?.joinEvent?.user?.id === this.me.id
      ) || null
    )
  }

  private get hasMyWantCircleProduct(): boolean {
    return this.myWantCircleProduct !== null
  }

  private async deleteCircleProduct() {
    const id = this.circleProduct.id
    await this.$apollo
      .mutate({
        mutation: DeleteCircleProductMutation,
        variables: { id },
      })
      .catch(() => this.$toast.error('削除に失敗しました'))

    this.onDeleteCircleProduct()
    this.$toast.success('削除しました')
  }

  private wantMeToo() {}
}
</script>
