import { debounce, throttle } from '@/utils/dom.ts'

export class CustomSelect {
  rootEl: Element

  multiple: boolean

  fetchUrl: string | null = null
  private fetchAbortController: AbortController | null = null

  /* Dropdown elements */
  dropdown: HTMLElement
  dropdownOptions: Map<string, HTMLElement> = new Map()
  optionsSearchText: Map<string, string> = new Map()

  dropdownSearch: HTMLInputElement
  dropdownSelectAllBtn: HTMLButtonElement | null = null
  dropdownUnselectAllBtn: HTMLButtonElement | null = null

  /* UI base elements */
  uiBox: HTMLElement
  placeholder: HTMLSpanElement
  valueLabel: HTMLSpanElement

  // Select element, hidden from the UI
  select: HTMLSelectElement

  // The current known value of the select
  current: string | string[]
  active: string | null = null
  isOpen: boolean = false

  get emptyValue(): string {
    return this.multiple
      ? ''
      : (this.select.firstElementChild as HTMLOptionElement).value
  }

  constructor(rootEl: Element, multiple: boolean) {
    this.multiple = multiple

    this.fetchUrl = rootEl.getAttribute('data-fetchurl')

    this.rootEl = rootEl

    this.dropdown = rootEl.querySelector('.ss-dropdown')!
    this.dropdownSearch = this.dropdown.querySelector(
      '.ss-dropdown-search input',
    )!

    this.dropdownSelectAllBtn = this.dropdown.querySelector(
      '.ss-dropdown-search .ss-select-all',
    )
    this.dropdownUnselectAllBtn = this.dropdown.querySelector(
      '.ss-dropdown-search .ss-unselect-all',
    )

    this.uiBox = rootEl.querySelector('.ss-box')!

    this.placeholder = this.uiBox.querySelector('.ss-placeholder')!
    this.valueLabel = this.uiBox.querySelector('.ss-value-label')!

    this.select = rootEl.querySelector('.ss-ghost-select')!

    this.current = this.calcCurrent()

    this.init()
  }

  calcCurrent = (): string | string[] => {
    if (this.multiple) {
      return Array.from(this.select.selectedOptions).map((opt) => opt.value)
    } else {
      return this.select.value
    }
  }

  init = () => {
    this.populateDropdown()

    for (const [key, opt] of this.dropdownOptions) {
      opt.addEventListener(
        'mousemove',
        throttle((e) => (key !== this.active ? this.setActive(key) : null), 25),
      )
    }

    this.uiBox.addEventListener('click', () => this.toggle())

    document.addEventListener('click', (e) => {
      if (!this.rootEl.contains(e.target as Node)) {
        this.close(false)
      }
    })

    document.addEventListener('keyup', (e) => {
      if (e.key === 'Escape') {
        this.close(false)
      }
    })

    document.addEventListener('keydown', (e) => {
      if (e.key === ' ' && this.uiBox === document.activeElement) {
        this.toggle()
      }

      if (this.isOpen && this.active !== null && e.key === 'Enter') {
        this.onOptionSelected(this.active)
      }

      if (this.isOpen && this.active !== null && e.key === 'ArrowDown') {
        this.moveActiveDown()
      }

      if (this.isOpen && this.active !== null && e.key === 'ArrowUp') {
        this.moveActiveUp()
      }
    })

    this.dropdownSearch?.addEventListener(
      'input',
      debounce((_e) => {
        this.search()
        this.fetchOptions()
      }, 250),
    )

    this.dropdownSearch?.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault()
      }
    })

    this.dropdown.addEventListener('click', (event) => {
      const target = event.target as HTMLElement

      if (target.classList.contains('ss-remove-icon')) {
        this.onOptionSelected(this.emptyValue)
        return
      }

      const option = target.closest('.ss-option')

      if (option !== null) {
        this.onOptionSelected(option.getAttribute('data-key'))
      }
    })

    this.select.addEventListener('change', this.update)

    this.dropdownSelectAllBtn?.addEventListener('click', (e) =>
      this.setAll(true),
    )
    this.dropdownUnselectAllBtn?.addEventListener('click', (e) =>
      this.setAll(false),
    )

    this.update()
    this.initLivewire()
    this.fetchOptions()
  }

  initLivewire = () => {
    const isLivewire = this.rootEl.hasAttribute('data-livewire')

    if (!isLivewire) {
      if (window['Livewire']) {
        console.info(
          '[SearchSelect] Be sure to include the livewire attribute when rendering inside a Livewire component',
        )
      }

      return
    }

    if (!window['Livewire']) {
      console.error('MISSING LIVEWIRE GLOBAL OBJECT!')
      return
    }

    window['Livewire'].hook('morph.updated', ({ el }) =>
      this.onLivewireUpdate(el),
    )

    window['Livewire'].hook('element.init', ({ el }) =>
      /* Timeout required because element.init is launched BEFORE wire:model takes effect */
      setTimeout(() => this.onLivewireUpdate(el), 50),
    )
  }

  onLivewireUpdate(el: HTMLElement) {
    if (el !== this.select) {
      return
    }

    // Hack to compare arrays equality in js
    if (JSON.stringify(this.current) !== JSON.stringify(this.calcCurrent())) {
      this.update()
    }

    setTimeout(() => this.populateDropdown(), 50)
  }

  open = () => {
    this.isOpen = true
    this.dropdown.classList.remove('hidden')
    setTimeout(() => this.dropdownSearch?.focus(), 25)

    this.setActive(this.dropdownOptions.keys().next().value ?? null)
  }

  close = (withFocus: boolean = true) => {
    this.isOpen = false
    this.dropdown.classList.add('hidden')

    if (withFocus) {
      this.uiBox.focus()
    }
  }

  toggle = () => {
    this.isOpen ? this.close() : this.open()
  }

  fetchOptions = async () => {
    if (!this.fetchUrl) {
      return
    }

    if (this.fetchAbortController) {
      this.fetchAbortController.abort() // Cancel the previous fetch
    }

    this.fetchAbortController = new AbortController() // Create a new AbortController
    const signal = this.fetchAbortController.signal // Get the AbortSignal

    let url = new URL(this.fetchUrl, window.location.origin) // Use URL constructor to handle existing params
    const searchString = this.dropdownSearch?.value.trim()

    if (searchString) {
      url.searchParams.append('q', searchString)
    }

    this.dropdown.classList.add('loading')

    try {
      const response = await fetch(url.toString(), { signal })

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`)
      }

      const data: { [key: string]: string } = await response.json() // Type assertion for the response

      // Clear existing options (except the empty value)
      const emptyValue = this.emptyValue // Save it before removing the HTML

      const selectedOptions = new Map<string, string>()

      // Remove all options aside from the empty one, and keep track of the selected ones
      for (const optEl of this.select.options) {
        if (optEl.value === emptyValue) continue
        if (optEl.selected) selectedOptions.set(optEl.value, optEl.innerText)
      }

      this.select.innerHTML = ''
      if (!this.multiple) {
        const emptyOption = document.createElement('option')
        emptyOption.value = emptyValue
        this.select.appendChild(emptyOption)
      }

      // Add the fetched options
      for (const valueKey in data) {
        const value = valueKey.toString()
        const optEl = document.createElement('option')
        optEl.value = value
        optEl.innerText = data[value] // Ensure innerText is set
        if (selectedOptions.has(value)) {
          optEl.selected = true
          selectedOptions.delete(value)
        }
        this.select.appendChild(optEl)
      }

      // If there are some selectedOptions not available anymore, we still need to add them to the select element or it would lose the reference
      for (const [value, label] of selectedOptions) {
        const optEl = document.createElement('option')
        optEl.value = value
        optEl.innerText = label // Ensure innerText is set
        optEl.selected = true
        optEl.setAttribute('data-hidden', 'true') // Those options should be hidden in the dropdown
        this.select.appendChild(optEl)
      }

      this.populateDropdown() // Update the dropdown UI
      this.update() // Update the selected value display
    } catch (error) {
      console.error('[SearchSelect] Error fetching options:', error)
      //  Optionally, display an error message to the user
    } finally {
      this.dropdown.classList.remove('loading')
      this.fetchAbortController = null
    }
  }

  search = () => {
    if (!this.dropdownSearch || this.fetchUrl !== null) {
      return
    }

    let s = this.dropdownSearch.value.toLowerCase().trim()
    if (s.length < 2) {
      s = ''
    }

    let newActive: string | null = null

    const toShow: HTMLElement[] = []
    const toHide: HTMLElement[] = []

    for (const [key, opt] of this.dropdownOptions) {
      const shouldShow =
        s === '' || this.optionsSearchText.get(key)?.includes(s)

      if (shouldShow) {
        if (opt.classList.contains('hidden')) {
          toShow.push(opt)
        }

        if (newActive === null) {
          newActive = key
        }
      } else {
        if (!opt.classList.contains('hidden')) {
          toHide.push(opt)
        }
      }
    }

    // Do all work in a single frame, avoiding multiple browser reflow & repaint
    requestAnimationFrame(() => {
      toShow.forEach((opt) => opt.classList.remove('hidden'))
      toHide.forEach((opt) => opt.classList.add('hidden'))

      this.setActive(newActive)
    })
  }

  populateDropdown = () => {
    const existingValues = new Set(this.dropdownOptions.keys())

    const template = this.rootEl.querySelector(
      '.ss-option-template',
    ) as HTMLTemplateElement

    const optionsWrapper = this.dropdown.querySelector('.ss-options')!

    for (const optEl of this.select.options) {
      if (optEl.value === this.emptyValue) continue

      // For each options in the root select element, add the equivalent option in the dropdown

      // To improve performance, do not recreate element if it already exists
      if (this.dropdownOptions.has(optEl.value)) {
        existingValues.delete(optEl.value)

        const dropdownOption = this.dropdownOptions.get(optEl.value)!
        dropdownOption.querySelector('span')!.innerText = optEl.innerText

        if (optEl.hasAttribute('data-hidden')) {
          dropdownOption.classList.add('hidden')
        } else {
          dropdownOption.classList.remove('hidden')
        }
        continue
      }

      const dropdownOption = (template.content.cloneNode(true) as HTMLElement)
        .firstElementChild as HTMLElement

      dropdownOption.setAttribute('data-key', optEl.value)
      dropdownOption.querySelector('span')!.innerText = optEl.label

      if (optEl.hasAttribute('data-hidden')) {
        dropdownOption.classList.add('hidden')
      } else {
        dropdownOption.classList.remove('hidden')
      }

      optionsWrapper.appendChild(dropdownOption)

      this.dropdownOptions.set(optEl.value, dropdownOption)
      this.optionsSearchText.set(optEl.value, optEl.label.toLowerCase())
    }

    // Existing values not removed at the previous step are no longer available, we can remove them
    existingValues.forEach((val) => {
      this.dropdownOptions.get(val)?.remove()

      this.dropdownOptions.delete(val)
      this.optionsSearchText.delete(val)
    })
  }

  onOptionSelected = (key: string | null) => {
    if (key == null) {
      key = this.emptyValue
    }

    if (this.multiple) {
      for (const option of this.select.options) {
        if (option.value !== key) continue

        option.selected = !option.selected
        break
      }
    } else {
      this.select.value = key
    }

    // Instead of directly calling update() we dispatch the change event
    // This ensures all listeners are notified properly.
    this.select.dispatchEvent(new Event('change'))

    if (!this.multiple) {
      this.close()
    }
  }

  setAll = (selected: boolean) => {
    for (const option of this.select.options) {
      const key = option.value.trim()

      option.selected = this.dropdownOptions
        .get(key)
        ?.classList.contains('hidden')
        ? option.selected
        : selected
    }
    this.select.dispatchEvent(new Event('change'))
  }

  setActive = (key: string | null) => {
    if (key === this.active) {
      return
    }

    if (this.active !== null) {
      this.dropdownOptions.get(this.active)?.classList.remove('active')
    }

    if (key !== null) {
      this.dropdownOptions.get(key)?.classList.add('active')

      this.dropdownOptions
        .get(key)
        ?.scrollIntoView({ block: 'nearest', behavior: 'smooth' })
    }

    this.active = key
  }

  moveActiveDown = () => {
    let next = false
    for (const key of this.dropdownOptions.keys()) {
      if (next) {
        this.setActive(key)
        break
      }
      if (key === this.active) {
        next = true
      }
    }
  }

  moveActiveUp = () => {
    let prev: string | null = null
    for (const key of this.dropdownOptions.keys()) {
      if (key === this.active) {
        if (prev) {
          this.setActive(prev)
        }
        break
      }

      prev = key
    }
  }

  update = () => {
    this.multiple ? this.updateMultiple() : this.updateSingle()
  }

  updateMultiple = () => {
    this.current = this.calcCurrent() as string[]

    const labels: string[] = []

    for (const [key, option] of this.dropdownOptions) {
      if (this.current.includes(key)) {
        option.classList.add('selected')
        labels.push(option.innerText.trim())
      } else {
        option.classList.remove('selected')
      }
    }

    if (labels.length > 0) {
      this.valueLabel.innerHTML = labels.join(', ')
      this.valueLabel.style.display = 'block'
      this.placeholder.style.display = 'none'
    } else {
      this.placeholder.style.display = 'block'
      this.valueLabel.style.display = 'none'
    }
  }

  updateSingle = () => {
    this.current = this.calcCurrent() as string

    this.dropdown
      .querySelectorAll('.ss-option.selected')
      .forEach((opt) => opt.classList.remove('selected'))

    let label = ''

    if (this.current !== this.emptyValue) {
      const dropdownOpt = this.dropdownOptions.get(this.current)

      if (dropdownOpt) {
        dropdownOpt.classList.add('selected')

        label = dropdownOpt.innerText ?? ''
      }
    }

    if (label !== '') {
      this.valueLabel.innerHTML = label
      this.valueLabel.style.display = 'block'
      this.placeholder.style.display = 'none'
    } else {
      this.placeholder.style.display = 'block'
      this.valueLabel.style.display = 'none'
    }
  }
}
