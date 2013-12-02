##################
# AJAX FUNCTIONS #
##################

$(document).ready () ->

  #setup ajax defaults
  $.ajaxSetup(
    error: (jqXHR, exception) ->
      if (jqXHR.status == 0)
        console.log('Unable to connect to server. Verify your network connection.');
      else if (jqXHR.status == 404)
        console.log('404 The requested page was not found.');
      else if (jqXHR.status == 500)
        console.log('500 Internal Server Error. Your session has expired, or you are not logged in.');
      else if (exception == 'parsererror')
        console.log('Looks like your session has expired. Try refreshing the page and signing in again.');
      else if (exception == 'timeout')
        console.log('Request has timed out. The server is probably under heavy load.');
      else if (exception == 'abort')
        console.log('Connection request has failed');
      else
        console.log('Unexpected Error.\n' + jqXHR.responseText);
  );

  #event handlers for the template buttons
  $("#createGroup").click( ()-> #stuff to do when create group is pressed

    groupname=$("#groupName").val();
    groupcode=$("#groupCode").val();
    description=$("#groupDescrip").val();
    privacy = $("#privacy").is(':checked')
    $("#createGroup").addClass("disabled")
    newGroup(groupcode,groupname,description,privacy&1)
  )

  #create group button is pressed
  $("#groups-templates").on('click','.create', ()->
    createGroup($(this).parents("tr.group").attr("id"));
    $(this).addClass("disabled")

  )

  #edit group button is pressed
  $("#groups-templates").on('click','.edit', ()->
    $("#modal-edit").modal('show');
    $("#modal-edit-code").val($(this).parents("tr.group").attr("id"));
    $("#modal-edit-name").val($(this).parent().siblings(".groupname").text())
    $("#modal-edit-description").val($(this).parent().siblings(".description").text())
    $("#modal-edit-privacy").prop("checked",parseInt($(this).parent().siblings(".privacy").text()))
  )

  #delete group button is pressed
  $("#groups-templates").on('click','.delete', ()->
    groupSelector = $(this).parents("tr.group")
    id = groupSelector.attr("id")
    deleteGroup(id)
  )

  #enable/disable the add group button
  $("#newgroupform input[type=text]").keyup( ()->
    if $("#groupCode").val() != "" && $("#groupName").val() != "" && $("#groupDescrip").val() != ""
      $("#createGroup").removeClass("disabled")
    else $("#createGroup").addClass("disabled")
  )


  #bind modal event handlers
  #bind modal close event
  $("#modal-edit").on("hide.bs.modal", () ->
    $("#modal-edit-code").val('');
    $("#modal-edit-name").val('');
    $("#modal-edit-description").val('')
    $("#modal-edit-privacy").prop('checked',false)
    $("#modal-edit-btn-save").removeClass("disabled")
  )

  #enable/disable the save changes button
  $("#modal-edit input[type=text]").keyup( ()->
    if $("#modal-edit-name").val() != "" && $("#modal-edit-description").val() != ""
      $("#modal-edit-btn-save").removeClass("disabled")
    else $("#modal-edit-btn-save").addClass("disabled")
  )

  #save button event handler
  $("#modal-edit-btn-save").click( ()->
    #save the editted group
    editGroup($("#modal-edit-code").val(),$("#modal-edit-name").val(),$("#modal-edit-description").val(),$("#modal-edit-privacy").is(':checked')&1)

  )




#add new group to save data array
newGroup = (groupcode,groupname,description,privacy) ->
  $.ajax(
    url: "admin/newTemplateGroup",
    type: 'POST',
    data:
      c: groupcode
      n: groupname
      d: description
      p: privacy
    success: (data) ->
      $("#empty-templates").remove();
      $("#groups-templates").append('<tr id="'+data+'" class="group"><td class="groupcode">'+groupcode+'</td><td class="groupname">'+ groupname+'</td><td class="description">'+description+'</td><td class="privacy">'+privacy+'</td><td class="actions"><button type="button" class="create btn btn-success">Create</button><button type="button" class="edit btn btn-warning">Edit</button><button type="button" class="delete btn btn-danger">Delete</button></td></tr>') #add the task to the table
      $("#groupName").val("");
      $("#groupCode").val("");
      $("#groupDescrip").val("");
      $("#privacy").prop('checked',false)
      $("#createGroup").addClass("disabled")
  )

#delete a group
deleteGroup = (groupcode) ->
  $.ajax(
    url: "/admin/deleteTemplateGroup",
    type: 'POST',
    data:
      c: groupcode
    success: () ->
      $("#groups-templates tr##{groupcode}").remove();
  )

#edit a group
editGroup = (groupcode,groupname,description,privacy) ->
  $.ajax(
    url: "/admin/editTemplateGroup",
    type: 'POST',
    data:
      c: groupcode
      n: groupname
      d: description
      p: privacy
    success: (data) ->
      $("#modal-edit").modal('hide');
      $("tr#"+groupcode+" .groupname").text(groupname);
      $("tr#"+groupcode+" .description").text(description);
      $("tr#"+groupcode+" .privacy").text(privacy&1);
  )

#create the group by copying it to the groups table
createGroup = (groupcode) ->
  $.ajax(
    url: "/admin/createTemplateGroup",
    type: 'POST',
    data:
      c: groupcode
    success: (data) ->
      console.log "group successfully created"
  )