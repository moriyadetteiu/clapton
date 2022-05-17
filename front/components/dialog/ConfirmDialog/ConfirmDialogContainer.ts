export type ConfirmPromiseFn = (value: boolean) => void
type PromiseListener = (resolve: ConfirmPromiseFn, message?: string) => void

export interface ConfirmDialogInterface {
  confirm(message?: string): Promise<boolean>
}

class ConfirmDialogContainer implements ConfirmDialogInterface {
  static instance: ConfirmDialogContainer
  private promise: Promise<any> | null = null
  private listener?: PromiseListener

  private constructor() {
    ConfirmDialogContainer.instance = this
  }

  public static getInstance(): ConfirmDialogContainer {
    if (!ConfirmDialogContainer.instance) {
      new ConfirmDialogContainer()
    }

    return ConfirmDialogContainer.instance
  }

  public listen(listener: PromiseListener) {
    this.listener = listener
  }

  public confirm(message?: string): Promise<boolean> {
    this.promise = new Promise<boolean>((resolve) => {
      if (!this.listener) {
        return
      }
      this.listener(resolve, message)
    })

    return this.promise
  }
}

export const confirmDialogContainer = ConfirmDialogContainer.getInstance()
