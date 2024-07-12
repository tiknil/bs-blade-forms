import { SearchSelect } from '@/components/search-select.ts'
import { MultiSelect } from '@/components/multi-select.ts'

document
  .querySelectorAll('div.ss-wrapper')
  .forEach((el) => new SearchSelect(el))

document.querySelectorAll('div.ms-wrapper').forEach((el) => new MultiSelect(el))

const observer = new MutationObserver(function (mutations) {
  mutations.forEach(function (mutation) {
    mutation.addedNodes.forEach(function (addedNode) {
      // HTMLElements are type 1
      if (addedNode.nodeType !== 1) return
      ;(addedNode as HTMLElement)
        .querySelectorAll('div.ss-wrapper')
        .forEach((el) => new SearchSelect(el))
      ;(addedNode as HTMLElement)
        .querySelectorAll('div.ms-wrapper')
        .forEach((el) => new MultiSelect(el))
    })
  })
})

observer.observe(document.body, {
  childList: true,
  subtree: true,
  attributes: false,
  characterData: false,
})
