/*Ajax call to login.php. Provides login functionality for web interface*/
$(document).ready(function(){
    $("#login").click(function(){
	$.ajax({type:"POST", 
		url: "login.php", 
		data: {user:$("#user").val(), password: $("#password").val()},
		success:function(data){
		    window.location.href = "CorvallisReuseRepair.php";
		},
		error:function(data){
		    alert("Invalid Login Information");
		}
	       });
    });
});
	    
