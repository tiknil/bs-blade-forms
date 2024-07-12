import { debounce, throttle } from '@/utils/dom.ts'

class SearchSelect {
  rootEl: Element

  /* Dropdown elements */
  dropdown: HTMLElement
  dropdownSearch: HTMLInputElement
  dropdownOptions: Map<string, HTMLElement> = new Map()

  /* UI base elements */
  uiBox: HTMLElement
  placeholder: HTMLSpanElement
  valueLabel: HTMLSpanElement

  // Select element, hidden from the UI
  select: HTMLSelectElement

  // The current known value of the select
  current: string
  active: string | null = null
  isOpen: boolean = false

  get emptyValue(): string {
    return (this.select.firstElementChild as HTMLOptionElement).value
  }

  constructor(rootEl: Element) {
    this.rootEl = rootEl

    this.dropdown = rootEl.querySelector('.ss-dropdown')!
    this.dropdownSearch = this.dropdown.querySelector(
      '.ss-dropdown-search input',
    )!

    this.uiBox = rootEl.querySelector('.ss-box')!

    this.placeholder = this.uiBox.querySelector('.ss-placeholder')!
    this.valueLabel = this.uiBox.querySelector('.ss-value-label')!

    this.select = rootEl.querySelector('.ss-ghost-select')!

    this.current = this.select.value

    this.init()
  }

  init = () => {
    this.populateDropdown()

    for (const [key, opt] of this.dropdownOptions) {
      opt.addEventListener('mousemove', () =>
        throttle(
          () => (key !== this.active ? this.setActive(key) : null),
          100,
        )(),
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
        this.close()
      }

      if (this.isOpen && this.active !== null && e.key === 'ArrowDown') {
        this.moveActiveDown()
      }

      if (this.isOpen && this.active !== null && e.key === 'ArrowUp') {
        this.moveActiveUp()
      }
    })

    this.dropdownSearch.addEventListener('input', () =>
      debounce(this.search, 250)(),
    )

    this.dropdown.addEventListener('click', (event) => {
      const target = event.target as HTMLElement

      if (target.classList.contains('ss-remove-icon')) {
        this.onOptionSelected(this.emptyValue)
        this.close()
        return
      }

      const option = target.closest('.ss-option')

      if (option !== null) {
        this.onOptionSelected(option.getAttribute('data-key'))
        this.close()
      }
    })

    this.select.addEventListener('change', this.update)

    this.update()

    this.initLivewire()
  }

  initLivewire = () => {
    const isLivewire = this.rootEl.hasAttribute('data-livewire')

    if (!this.rootEl.hasAttribute('data-livewire')) {
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

    if (this.current !== this.select.value) {
      this.update()
    }

    setTimeout(() => this.populateDropdown(), 50)
  }

  open = () => {
    this.isOpen = true
    this.dropdown.classList.remove('hidden')
    setTimeout(() => this.dropdownSearch.focus(), 25)

    this.setActive(this.dropdownOptions.keys().next().value)
  }

  close = (withFocus: boolean = true) => {
    this.isOpen = false
    this.dropdown.classList.add('hidden')

    this.dropdownSearch.value = ''
    this.dropdownSearch.dispatchEvent(new Event('input'))

    if (withFocus) {
      this.uiBox.focus()
    }
  }

  toggle = () => {
    this.isOpen ? this.close() : this.open()
  }

  search = () => {
    const s = this.dropdownSearch.value.toLowerCase().trim()

    let newActive: string | null = null

    for (const [key, opt] of this.dropdownOptions) {
      const shouldShow = s === '' || opt.innerText.toLowerCase().includes(s)

      if (shouldShow) {
        opt.classList.remove('hidden')

        if (newActive === null) {
          newActive = key
        }
      } else {
        opt.classList.add('hidden')
      }
    }

    this.setActive(newActive)
  }

  populateDropdown = () => {
    const existingValues = new Set(this.dropdownOptions.keys())

    const template = this.rootEl.querySelector(
      '.ss-option-template',
    ) as HTMLTemplateElement

    const optionsWrapper = this.dropdown.querySelector('.ss-options')!

    this.select.querySelectorAll('option').forEach((option) => {
      if (option.value === this.emptyValue) return

      if (this.dropdownOptions.has(option.value)) {
        existingValues.delete(option.value)

        this.dropdownOptions
          .get(option.value)!
          .querySelector('span')!.innerText = option.innerText
        return
      }

      const dropdownOption = (template.content.cloneNode(true) as HTMLElement)
        .firstElementChild as HTMLElement

      dropdownOption.setAttribute('data-key', option.value)
      dropdownOption.querySelector('span')!.innerText = option.label

      optionsWrapper.appendChild(dropdownOption)

      this.dropdownOptions.set(option.value, dropdownOption)
    })

    existingValues.forEach((val) => {
      this.dropdownOptions.get(val)?.remove()

      this.dropdownOptions.delete(val)
    })
  }

  onOptionSelected = (key: string | null) => {
    if (key == null) {
      key = this.emptyValue
    }

    this.select.value = key

    // Instead of directly calling update() we dispatch the change event
    // This ensures all listeners are notified properly.
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
    this.current = this.select.value

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

document
  .querySelectorAll('div.ss-wrapper')
  .forEach((el) => new SearchSelect(el))

// Check if added elements are search-select elements
const observer = new MutationObserver(function (mutations) {
  mutations.forEach(function (mutation) {
    mutation.addedNodes.forEach(function (addedNode) {
      // HTMLElements are type 1
      if (addedNode.nodeType !== 1) return
      ;(addedNode as HTMLElement)
        .querySelectorAll('div.ss-wrapper')
        .forEach((el) => new SearchSelect(el))
    })
  })
})

observer.observe(document.body, {
  childList: true,
  subtree: true,
  attributes: false,
  characterData: false,
})
