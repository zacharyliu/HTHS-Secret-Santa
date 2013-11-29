$('document').ready () ->

  #edit group button is pressed
  $(".grp-edit").on('click', ()->
    $("#modal-edit-grp").modal('show');
    $("#modal-edit-grp-code").val($(this).parent().siblings(".groupcode").text())
    $("#modal-edit-grp-code-hidden").val($(this).parent().siblings(".groupcode").text())
    $("#modal-edit-grp-name").val($(this).parent().siblings(".groupname").text())
    $("#modal-edit-grp-description").val($(this).parent().siblings(".description").text())
  )

  #bind modal event handlers
  #bind modal close event
  $("#modal-edit-grp").on("hide.bs.modal", () ->
    $("#modal-edit-grp-code").val('');
    $("#modal-edit-grp-hidden").val('');
    $("#modal-edit-grp-name").val('');
    $("#modal-edit-grp-description").val('')
    $("#modal-edit-btn-save").removeClass("disabled")
  )

  #enable/disable the save changes button
  $("#modal-edit input[type=text]").keyup( ()->
    if $("#modal-edit-name").val() != ""
      $("#modal-edit-btn-save").removeClass("disabled")
    else $("#modal-edit-btn-save").addClass("disabled")
  )