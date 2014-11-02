$(document).ready(function(){
  $("#add-group").click(function(){showModal("AdminCore","configGroupEdit",{"i_g":$(this).data("group")});});
  $("#add-param").click(function(){showModal("AdminCore","configParamEdit",{"i_g":$(this).data("group")});});
  $("#edit-group").click(function(){showModal("AdminCore","configGroupEdit",{"i_itm_id":$(this).data("group"),"i_g":$(this).data("group")});});
});