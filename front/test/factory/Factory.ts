import faker from 'faker'

export default abstract class Factory<T extends Object> {
  protected faker: Faker.FakerStatic = faker

  protected abstract provide(): T

  public make(attribute: Partial<T> = {}): T {
    return {
      ...this.provide(),
      ...attribute,
    }
  }
}
