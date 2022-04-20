import { CircleList } from '~/apollo/graphql'
import { ConditionItem, Filter } from './filterInterfaces'

export default abstract class AbstractIdSelectionFilter implements Filter {
  protected abstract readonly label: string
  protected abstract readonly key: keyof CircleList
  private readonly conditionItems: ConditionItem[]

  constructor(conditionItems: ConditionItem[]) {
    this.conditionItems = conditionItems
  }

  public getConditionItems(): ConditionItem[] {
    return this.conditionItems
  }

  public filter(filterConditions: any, item: CircleList): boolean {
    const result =
      filterConditions[this.key].length === 0 ||
      filterConditions[this.key].includes(item[this.key])
    return result
  }

  public getKey(): string {
    return this.key
  }

  public getLabel(): string {
    return this.label
  }
}
