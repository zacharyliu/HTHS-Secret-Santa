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
  $("#modal-edit-grp input[type=text]").keyup( ()->
    if $("#modal-edit-grp-name").val() != ""
      $("#modal-edit-grp-btn-save").removeClass("disabled")
    else $("#modal-edit-grp-btn-save").addClass("disabled")
  )


  #Edit Interests
  $("#interests-edit").on('click', (e) ->
    e.preventDefault();
    $("#interests-form").toggle();
  )

  #count characters
  max = 300;
  len = $('#interests-textarea').val().length;
  diff = max-len
  $('#char-count').text(diff + ' characters left');
  $('#interests-textarea').keyup( ()->
    len = $('#interests-textarea').val().length;
    diff = max-len
    if (diff < 0)
      $('#char-count').text((len-max) + ' characters over');
    else
      $('#char-count').text((max-len) + ' characters left');

  );

  setInterval( ()->
    if ($("#interests-textarea").val().length <=300)
      $("#interests-submit").removeClass("disabled");
    else
      $("#interests-submit").addClass("disabled");

  , 200)



  #Get user interests
