QTags.addButton('wpfr_button', wfrReplies.i18n.wfrBtn, wfrOpenThickbox, '', '', wfrReplies.i18n.wfrTip, 1, 'replycontent')

function wfrOpenThickbox() {
  let tbHeight = 90
  let wfrTbContent = ''

  if (wfrReplies.replies.length > 0) {
    wfrTbContent = `<select id="wpfr-reply-list" style="width:100%;margin:10px 0">`

    wfrReplies.replies.forEach(reply => {
      wfrTbContent += `<option value="${reply.slug}">${reply.title}</option>`
    })

    wfrTbContent += `</select><a href="#" id="wpfr-insert-reply" class="button-primary alignright">${wfrReplies.i18n.insert}</a>`
    wfrTbContent += `<a href="#" id="wpfr-cancel-reply" class="button-secondary alignleft">${wfrReplies.i18n.cancel}</a>`
  } else {
    wfrTbContent = `<p class="wpfr-no-reply" style="margin:11px 0 0 0">${wfrReplies.i18n.pleaseAdd} <a href="${wfrReplies.i18n.optionsUrl}">${wfrReplies.i18n.here}</a></p>`
    tbHeight = 45
  }

  tb_show(wfrReplies.i18n.pleaseSelect, `#TB_inline?width=300&height=${tbHeight}&inlineId=wpfr_button`)
  document.getElementById('TB_ajaxContent').innerHTML = wfrTbContent
}

document.addEventListener('click', (e) => {
  const target = e.target

  if (target.id === 'wpfr-insert-reply') {
    e.preventDefault()

    let selectedSlug = document.getElementById('wpfr-reply-list').value
    content = wfrReplies.replies.find(reply => reply.slug === selectedSlug)?.content

    QTags.insertContent(content)
    tb_remove()
  } else if (target.id === 'wpfr-cancel-reply') {
    e.preventDefault()
    tb_remove()
  }
})