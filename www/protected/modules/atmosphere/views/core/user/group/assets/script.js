$(document).ready(function(){
  $(".admin-group-settings").click(function(e){e.preventDefault();showModal("AdminCore","userGroupRightsEdit",{"i_itm_id":$(this).data('group')});return false;});
});