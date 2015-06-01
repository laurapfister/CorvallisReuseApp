
$(document).ready(function(){
    $("#login").click(function(){
	$.ajax({type:"POST", 
		url: "login.php", 
		data: {"user":$("#user").val(), "password": $("#password").val},
		success:function(data){
		    window.location.href = "CorvallisReuseRepair.html";
		},
		error:function(data){
		    alert("Invalid Login Information");
		}
	       });
    });
});
	    
