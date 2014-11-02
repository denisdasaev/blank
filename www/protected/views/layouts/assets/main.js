function rc(driver,method,onSuccess,data,authorized,onError,loadingSelector){
  if(arguments.length<5){
    authorized=true;
    onError=function(){if(typeof debugMode!="undefined"&&debugMode)alert("Успешное выполнение запроса Ajax с получением пустого ответа.");}
  }
  if(arguments.length<7)loadingSelector="#main-loading";
  var ctkn=$("#ctkn").val();
  if(!authorized||ctkn=="undefined")ctkn="";
  $.ajax({
    "url":"/do/ajax",
    "type":"POST",
    "data":{"request":{"drv":driver,"proc":method,"prms":data,"ctkn":ctkn}},
    "dataType":"HTML",
    "beforeSend":function(xhr){if(loadingSelector!="")$(loadingSelector).show();},
    "success":function(data,textStatus,xhr){
//      data=trim(data);
      if(data!=="")onSuccess(data);
      else onError();
    },
    "error":function(xhr,textStatus,errorObj){if(arguments.length>5)onError();else{if(typeof debugMode!="undefined"&&debugMode)alert("При выполнении запроса Ajax возникла ошибка!\n\n"+textStatus+"\n\n"+errorObj+"\n\n"+xhr.responseText);}},
    "complete":function(xhr,textStatus){if(loadingSelector!="")$(loadingSelector).hide();}
  });
}

function showModal(driver,method,data){
  if(arguments.length<3)data={};
  rc(driver,method,function(response){
    if(response!=""){
      $("#rubber-box").html(response);
      $("#modal-content").modal("show");
    }
  },data);
}

function noBubbling(e){
  if (!e) var e = window.event;
  e.cancelBubble = true;
  if (e.stopPropagation){
    e.stopPropagation();
  }
}

//function parseURL(url) {
//  var a =  document.createElement('a');
//  a.href = url;
//  return {
//    source: url,
//    protocol: a.protocol.replace(':',''),
//    host: a.hostname,
//    port: a.port,
//    query: a.search,
//    params: (function(){
//      var ret = {},
//        seg = a.search.replace(/^\?/,'').split('&'),
//        len = seg.length, i = 0, s;
//      for (;i<len;i++) {
//        if (!seg[i]) { continue; }
//        s = seg[i].split('=');
//        ret[s[0]] = s[1];
//      }
//      return ret;
//    })(),
//    file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
//    hash: a.hash.replace('#',''),
//    path: a.pathname.replace(/^([^\/])/,'/$1'),
//    relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
//    segments: a.pathname.replace(/^\//,'').split('/')
//  };
//}
/*
var myURL = parseURL('http://abc.com:8080/dir/index.html?id=255&m=hello#top');

myURL.file;     // = 'index.html'
myURL.hash;     // = 'top'
myURL.host;     // = 'abc.com'
myURL.query;    // = '?id=255&m=hello'
myURL.params;   // = Object = { id: 255, m: hello }
myURL.path;     // = '/dir/index.html'
myURL.segments; // = Array = ['dir', 'index.html']
myURL.port;     // = '8080'
myURL.protocol; // = 'http'
myURL.source;   // = 'http://abc.com:8080/dir/index.html?id=255&m=hello#top'
*/

$.extend({
  getUrlVars: function(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getUrlVar: function(name){
    return $.getUrlVars()[name];
  }
});

//function trim(str){return str.replace(/^\s\s*/,'').replace(/\s\s*$/,'');}

$(document).ready(function(){$("#w-shadow,#main-loading").hide();});