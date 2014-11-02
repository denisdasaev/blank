$(document).ready(function(){
  function regCheckInputs(){
    if($("#reg-login-icon").hasClass("glyphicon-ok")&&$("#reg-password-icon").hasClass("glyphicon-ok")
      &&$("#reg-password2-icon").hasClass("glyphicon-ok")&&$("#reg-email-icon").hasClass("glyphicon-ok"))
      $("#reg-btn-ok").removeAttr("disabled");
    else $("#reg-btn-ok").attr("disabled","disabled");
  }
  $("#edit-login").change(function(){
    var val=$(this).val();
    $("#reg-login-icon").attr("class","glyphicon").hide();
    $("#reg-login-group").attr("class","form-group");
    rc('Do','validRegLogin',function(data){
      $("#reg-login-icon").attr("class","glyphicon glyphicon-"+(data=="1"?"ok text-success":"ban-circle text-danger")).show();
      $("#reg-login-group").attr("class","form-group has-"+(data=="1"?"success":"error"));
      regCheckInputs();
    },{'s_login':val},false,null,'#reg-login-loading');
  });
  $("#edit-password").change(function(){
    var val=$(this).val();
    $("#reg-password-icon").attr("class","glyphicon").hide();
    $("#reg-password-group").attr("class","form-group");
    rc('Do','validRegPassword',function(data){
      $("#reg-password-icon").attr("class","glyphicon glyphicon-"+(data=="1"?"ok text-success":"ban-circle text-danger")).show();
      $("#reg-password-group").attr("class","form-group has-"+(data=="1"?"success":"error"));
      $("#edit-password2").trigger('change');
      regCheckInputs();
    },{'s_password':val},false,null,'#reg-password-loading');
  });
  $("#edit-password2").change(function(){
    var val=$(this).val();
    var pw=$("#edit-password").val();
    $("#reg-password2-icon").attr("class","glyphicon").hide();
    $("#reg-password2-group").attr("class","form-group");
    rc('Do','validRegPassword2',function(data){
      $("#reg-password2-icon").attr("class","glyphicon glyphicon-"+(data=="1"?"ok text-success":"ban-circle text-danger")).show();
      $("#reg-password2-group").attr("class","form-group has-"+(data=="1"?"success":"error"));
      regCheckInputs();
    },{'s_password':pw,'s_password2':val},false,null,'#reg-password2-loading');
  });
  $("#edit-email").change(function(){
    var val=$(this).val();
    $("#reg-email-icon").attr("class","glyphicon").hide();
    $("#reg-email-group").attr("class","form-group");
    rc('Do','validRegEmail',function(data){
      $("#reg-email-icon").attr("class","glyphicon glyphicon-"+(data=="1"?"ok text-success":"ban-circle text-danger")).show();
      $("#reg-email-group").attr("class","form-group has-"+(data=="1"?"success":"error"));
      regCheckInputs();
    },{'s_email':val},false,null,'#reg-email-loading');
  });
});