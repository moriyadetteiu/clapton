export interface ValidationItem {
  rules?: string
  attribute?: string
}

interface ValidationItems {
  [name: string]: ValidationItem
}

export default class Validation {
  protected items: ValidationItems

  constructor(items: ValidationItems) {
    this.items = items
  }

  public getItem(name: string): ValidationItem {
    return this.items[name]
  }

  public getItems(): ValidationItems {
    return this.items
  }
}
