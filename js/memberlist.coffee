$(document).ready () ->
  # clear the member list modal on close, to load new content
  $('#modal-member-list').on 'hidden.bs.modal', () ->
    $(this).removeData('bs.modal')