import {
  EventDate,
  CirclePlacementClassification,
  WantPriority,
  CircleList,
  CircleProductClassification,
} from '~/apollo/graphql'

export interface ConditionItem {
  id: string
  name: string
}

export type FilterConditionItems = {
  eventDates: EventDate[]
  circlePlacementClassifications: CirclePlacementClassification[]
  wantPriorities: WantPriority[]
  circleProductClassifications: CircleProductClassification[]
}

export type FilterConditions = {
  [T in keyof CircleList]?: string[]
}

export interface Filter {
  getConditionItems(): ConditionItem[]
  filter(filterConditions: any, item: CircleList): boolean
  getKey(): string
  getLabel(): string
}
