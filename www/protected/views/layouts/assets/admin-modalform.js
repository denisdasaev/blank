$(document).ready(function(){
//  $("#modal-form").on("submit",function(e){
//    e.preventDefault();
//    alert($(this).serialize());
//  });
  $("#mf-button-ok > button").click(function(){
    $("#main-loading").show();
    $(this).text("Подождите...").attr("disabled","disabled");
    $("#mf-button-delete > a").addClass("disabled");
    $("#mf-button-cancel > button").attr("disabled","disabled");
    $("#modal-form").submit();
  });
});