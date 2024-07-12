import { CustomSelect } from '@/components/custom-select.ts'

export class MultiSelect extends CustomSelect {
  constructor(rootEl: Element) {
    super(rootEl, true)
  }
}
