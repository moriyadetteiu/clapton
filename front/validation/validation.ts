interface ValidationItems {
  [name: string]: {
    rule?: string
    attribute?: string
  }
}

export default class Validation {
  protected items: ValidationItems

  constructor(items: ValidationItems) {
    this.items = items
  }
}
