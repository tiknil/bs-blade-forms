import {SearchSelect} from '@/components/search-select.ts'
import {MultiSelect} from '@/components/multi-select.ts'

const initChilds = (root: ParentNode) => {
  root.querySelectorAll('div.ss-wrapper')
    .forEach((el) => new SearchSelect(el))

  root.querySelectorAll('div.ms-wrapper')
    .forEach((el) => new MultiSelect(el))

}

initChilds(document)

const observer = new MutationObserver(function (mutations) {
  mutations.forEach(function (mutation) {
    mutation.addedNodes.forEach(function (addedNode) {
      // HTMLElements are type 1
      if (addedNode.nodeType !== 1) return

      initChilds(addedNode as ParentNode)

    })
  })
})

observer.observe(document.body, {
  childList: true,
  subtree: true,
  attributes: false,
  characterData: false,
})
