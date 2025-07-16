import { CustomSelect } from '@/components/custom-select.ts'
import { debounce } from '@/utils/dom'

export class Autocomplete extends CustomSelect {
  private readonly input: HTMLInputElement
  private latestPreview: string = '' // keep track of the latest preview forced, to check if current input is the selected one

  get searched(): string {
    return this.input?.value ?? ''
  }

  constructor(rootEl: Element) {
    super(rootEl, false)

    this.input = rootEl.querySelector('.ac-input') as HTMLInputElement

    // Use the main input for search/filtering
    this.input.addEventListener(
      'input',
      debounce((_e) => {
        this.search()
        this.fetchOptions()
      }, 250),
    )

    this.input?.addEventListener('keydown', (e) => {
      if (!this.isOpen) this.open()
      if (e.key === 'Enter') {
        e.preventDefault()
      }
    })

    // Listen to input focus to show dropdown
    this.input.addEventListener('focus', () => {
      this.open()
      this.search()

      this.input.select()
    })

    this.input.addEventListener('blur', () => {
      // Each click on a dropdown option triggers this eventListener immediately
      // Without the timeout, it would force the emptyValue instead of selected option
      setTimeout(() => {
        if (
          this.input.value !== '' &&
          this.input.value !== this.latestPreview
        ) {
          this.onOptionSelected(this.emptyValue)
        }
      }, 250)
    })
  }

  onOpen = () => {}

  onClose = () => {}

  setPreview(label: string) {
    // Override of base method
    this.input.value = label.trim()
    this.latestPreview = label.trim()
  }
}
