import { CustomSelect } from '@/components/custom-select.ts'

export class SearchSelect extends CustomSelect {
  constructor(rootEl: Element) {
    super(rootEl, false, false)
  }
}
