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

let timeout = 0
export const debounce =
  <T>(fn: (...args: T[]) => void, delay: number) =>
  (...args: T[]) => {
    clearTimeout(timeout)
    // adds `as unknown as number` to ensure setTimeout returns a number
    // like window.setTimeout
    timeout = setTimeout(() => fn(...args), delay)
  }
