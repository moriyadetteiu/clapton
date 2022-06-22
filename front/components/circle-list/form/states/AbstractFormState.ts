import FormStateInterface from './FormStateInterface'

export default abstract class AbstractFormState<
  Attrs extends Object,
  On extends Object
> implements FormStateInterface
{
  private attrs: Attrs
  private on: On
  protected abstract componentName: string

  public constructor(attrs: Attrs, on: On) {
    this.attrs = attrs
    this.on = on
  }

  public getAttrs(): Attrs {
    return this.attrs
  }

  public getOn(): On {
    return this.on
  }

  public getComponentName(): string {
    return this.componentName
  }
}
