$(document).ready(function(){
  $("#edit-del-img").click(function(){if(confirm("Удалить картинку?")){var confId=$(this).data("confId");rc("AdminCore","deletePic",function(response){if(response!="")$("#edit-file-input").html(response);},{"i_itm_id":confId});}return false;});
});