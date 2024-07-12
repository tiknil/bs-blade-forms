/*
 * Permette di aggiungere un event listener su degli elementi dinamici in cambiamento
 * (es. dentro una tabella quando si cambia pagina)
 */
export function addChildEventListener(
  element: HTMLElement,
  event: string,
  selector: string,
  callback: (target: Element) => void,
  stopPropagation: boolean = false,
) {
  element.addEventListener(event, function (e) {
    if (!e.target) return
    const target = (e.target as HTMLElement).closest(selector)

    if (target) {
      e.preventDefault()
      if (stopPropagation) {
        e.stopImmediatePropagation()
        e.stopPropagation()
      }
      callback(target)
    }
  })
}

let dispatchTimeout = 0
export const debounce =
  <T>(fn: (...args: T[]) => void, delay: number) =>
  (...args: T[]) => {
    clearTimeout(dispatchTimeout)
    // adds `as unknown as number` to ensure setTimeout returns a number
    // like window.setTimeout
    dispatchTimeout = setTimeout(() => fn(...args), delay)
  }

let lastThrottleRun: null | number = null
let throttleTimeout: number = 0

export const throttle =
  <T>(fn: (...args: T[]) => void, delay: number) =>
  (...args: T[]) => {
    if (lastThrottleRun === null) {
      fn(...args)

      lastThrottleRun = Date.now()
    } else {
      const diff = Date.now() - lastThrottleRun!
      clearTimeout(throttleTimeout)

      throttleTimeout = setTimeout(() => {
        if (diff >= delay) {
          fn(...args)

          lastThrottleRun = Date.now()
        }
      }, delay - diff)
    }
  }
