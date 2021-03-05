function errorx(err){
    $('.error_note').text(err);
    $('.error_note').slideDown(200)
}
$('.logo, .site-logo, .llogo').click(function(){
	  window.location="https://www.peerlessmag.com";
});
var begin = new Mprogress({
template: 3,
parent: '.lloader'
});
function conB(v, b) {
            var ee;
            if (window.XMLHttpRequest) {
                ee = new XMLHttpRequest();
            } else {
                ee = new ActiveXObject("Microsoft.XMLHTTP");
            };
            ee.open('POST','main.php',true);
            ee.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
            ee.onreadystatechange = function() {
                    if (ee.status == 200 && ee.readyState == 4) {
                        begin.end(true);
                        if (typeof b == "function") {
                            if(ee.responseText=="unauth"){
                                
                                //do something here
                            }else{
                            b(ee.responseText);
                            }
                     };
                }
            }
            window.scroll(0,0)
            begin.start();
			ee.send(v);
			return true;
        }

function validate(input){
if($(input).attr('type')=='email'||$(input).attr('name')=='email'){if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/)==null/*regex bla bla*/){return false;}}else if($(input).attr('type')=="password" && $(input).attr('name')=="repeat-pass" && $(input).val()!=$('.mainpass').val()){
    $(input).attr('data-validate','Repeat Password Is Not Equal With Password');  
return false;

}else if($(input).attr('name')=="pass" && validatePass(input)){
return false;
}else if($(input).val().trim()==''){
    return false;
}
};
$('.input200').on('keyup keypress keydown change blur',function(p){
      if($(this).val().length == 7){
        p.preventDefault();
          $(this).attr('disabled',true);
          hideValidate(this)
          $(this).addClass('true-validate')
          conB('do=confirm&pin='+encodeURIComponent($(this).val()),function(v){
              if(v=="done"){
                  window.location = "?repass";
              }else{
                errorx(v);                
                $('.input200').attr('disabled',false)
                $('.input200').val("");
                $('.input200').removeClass('true-validate')
                showValidate($('.input200'));
              }
          })
      }
});

function validatePass(p){
    //return true if error
	var noNum=noChar=noSpec=false,pass = $(p).val();
if(pass!=""){
if(/[\d]/g.test(pass)){
    noNum = true;
}
if(/[\W]/g.test(pass)){
    noSpec = true;
}
    if(pass.length>=8){
noChar = true;
    }
		if((noChar && noNum) || (noChar && noSpec)){
			return false;
		}else{
    return true;
}
	}else{
		return true;
	}
};

function showValidate(input){var thisAlert=$(input);$(thisAlert).addClass('alert-validate');
}

function hideValidate(input){var thisAlert=$(input);$(thisAlert).removeClass('alert-validate');
$('.error_note').css('display','none');
}

$('.input100').each(function(){
    $(this).focus(function(){hideValidate(this);$(this).removeClass('true-validate');});});

$('.input100').each(function(){$(this).on('blur',function(){if($(this).val().trim()!=""){$(this).addClass('has-val');}else{$(this).removeClass('has-val');}})
})

$('.input100').each(function(){$(this).on('blur',function(){if(validate(this)==false){showValidate(this);}
else{$(this).addClass('true-validate');}})})

var input=$('.input100');


$('.recovery-form').on('submit',function(){var check=true;for(var i=0;i<input.length;i++){
$('.error_note').css('display','none');
    if(validate(input[i])==false){
  showValidate(input[i]);check=false;
    }
}
if(check){
  conB('do=npass&new='+encodeURIComponent($(input[0]).val()),function(x){
      if (x == "done") {
 window.location = "dashboard";
  } else{
      errorx(x);
  }
  });   
}
    return false;
});

function ggo(t){    
    var check=true;
    for(var i=0;i<input.length;i++){
$('.error_note').css('display','none');
    if(validate(input[i])==false){
  showValidate(input[i]);check=false;
    }
}
if(check){
  conB('do=in&user='+encodeURIComponent($(input[0]).val())+'&pass=' +encodeURIComponent($(input[1]).val())+'&typ='+t,function(x){
      if (x == "true") {
 window.location = "dashboard";
  } else {
      errorx(x);
  }
  });   
}
    return false;
};

function show_pass(e,pass){
    if($(pass).attr('type')=="password"){
        $(pass).attr('type','text');
        $(e).addClass('fa-eye-slash');
        $(e).removeClass('fa-eye');
    }else{
        $(pass).attr('type','password');
        $(e).removeClass('fa-eye-slash');
        $(e).addClass('fa-eye');
    }
}
function valid(){
    if(validate('.inputx')!=false){
      conB('type="Recovery Mail"&do=recover&user='+encodeURIComponent($('.inputx').val()),function(x){
    if (x == "done") {
 window.location = "?recovery";
  } else {
errorx(x);
  }
});
}else{errorx('Your Email Is Required To Recover Password');}
}
