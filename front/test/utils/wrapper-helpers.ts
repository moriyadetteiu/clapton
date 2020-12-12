import Vue from 'vue'
import { Wrapper } from '@vue/test-utils/types/index'

const ERROR_MESSAGE_SELECTOR: string = '.v-messages.error--text'

export const findAllErrorMessages = <V extends Vue>(
  wrapper: Wrapper<V>
): Array<String> => {
  return wrapper
    .findAll(ERROR_MESSAGE_SELECTOR)
    .wrappers.map((value) => value.text())
}
