document.addEventListener('DOMContentLoaded', function () {
  initWPEditor()
})

function initWPEditor() {
  const textareas = document.querySelectorAll('textarea')

  textareas.forEach(function (textarea) {
    wp.editor.initialize(
      textarea.id,
      {quicktags: true}
    )
  })
}

const repliesForm = document.getElementById('frequently-replies-options-form')
const repliesList = document.getElementById('replies-item-container')
const addBtn = document.getElementById('add-another-reply')
const saveBtn = document.getElementById('save-options')
const blocker = document.getElementById('wpfr-blocker')
const snackBar = document.getElementById('wpfr-snackbar')

addBtn.addEventListener('click', function (e) {
  e.preventDefault()

  repliesList.insertAdjacentHTML('beforeend', wfrNewReply())
  initWPEditor()
})

document.addEventListener('click', (e) => {
  const target = e.target

  if (target.classList.contains('wpfr-remove')) {
    e.preventDefault();

    if (document.getElementsByClassName('wpfr-remove').length > 1) {
      target.parentNode.remove()
    } else {
      const inputText = target.parentNode.querySelector('input[type="text"]')
      const textarea = target.parentNode.querySelector('textarea')

      if (inputText) {
        inputText.value = ''
      }

      if (textarea) {
        textarea.value = ''
      }
    }
  }
});

repliesForm.addEventListener('submit', function (e) {
  e.preventDefault()
  wfrBlockFrom()

  const formData = new FormData(this)

  fetch(
    ajaxurl, {
      method: 'post',
      body: formData,
    }
  ).then(response => response.json())
    .then(result => {
        wfrUnblockFrom()

        if (result.success) {
          showSnackBar(result.data.message)
        } else {
          showSnackBar(result.data.message, 'error')
        }
      }
    ).catch(
    (error) => {
      showSnackBar(`${wfrOptions.i18n.error} : ${error.text}`, 'error')
      console.error('Error:', error);
      wfrUnblockFrom()
    }
  )
})

function wfrNewReply() {
  let replyCount = repliesList.children.length

  return `<li><div class="form-group row">
        <label for="replytitle-${replyCount}" class="col-sm-2 col-form-label">${wfrOptions.i18n.title}</label>
        <div class="col-sm-10"><input type="text" name="replies[${replyCount}][title]" class="regular-text" id="replytitle-${replyCount}" value=""></div></div>
        <a class="wpfr-remove notice-dismiss" href="#">${wfrOptions.i18n.remove}</a><div class="form-group row">
        <label for="replycontent-${replyCount}" class="col-sm-2 col-form-label">${wfrOptions.i18n.content}</label>
        <div class="col-sm-10">
            <div id="replycontainer-${replyCount}">
                <div id="wp-replycontent-wrap-${replyCount}" class="wp-core-ui wp-editor-wrap html-active">
                    <div id="wp-replycontent-editor-container-${replyCount}" class="wp-editor-container">
                        <textarea class="wp-editor-area" rows="5" tabindex="104" cols="40" name="replies[${replyCount}][content]" id="replycontent-${replyCount}"></textarea>
                    </div>
                </div>
            </div>
        </div></div></li>`
}

function wfrBlockFrom() {
  addBtn.classList.add('disabled')
  saveBtn.disabled = true
  blocker.style.display = 'block'
}

function wfrUnblockFrom() {
  addBtn.classList.remove('disabled')
  saveBtn.disabled = false
  blocker.style.display = 'none'
}

function showSnackBar(message, type = 'success') {
  snackBar.innerHTML = message
  snackBar.classList.add(type, 'show')

  setTimeout(function () {
    snackBar.classList.remove(type, 'show')
  }, 3000);
}