$(document).ready(function(){
    
    $("#logout").bind('click', function(){
	$.ajax({type:"GET",
		url:"logout.php",
		success:function(data){
		    window.location.href = 'crrlogin.html';
		}
	       });
    });
});
