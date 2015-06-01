var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";

$(document).ready(function(){
    

    var cat_edits = [];
    var all_cats = "";

    var get_all_cats = $.ajax({type:"GET",
				data:"json",
				url: base_url+"/reuseCategory"});
	
    function add_cats(){
	get_all_cats.done(function(data){
	    var cats = "<select name='select_cat' class='add-cat' >";
	    for(var i = 0; i < data.length; i++){
		cats += "<option class='cat_option' value=" +data[i].categoryId +">";
		cats += data[i].categoryName;
		cats += "</option>";
	    }
	    cats += "</select>";
	    $("#add_cats").append(cats);
	});
    }
	

    var get_businesses = $.ajax({type:"GET",
				 url: base_url+"/reuse",
				 dataType: 'json'
				});


    var delete_cat = function(){
	$(".remove_cat").bind('click', function(){
	    $(this).parent().remove();
	});
    }


    var delete_assoc = function(id, category){
	$.ajax({type:"DELETE",
		url: base_url + "/reuse/"+category+"/"+id});
	}
    
    var create_assoc = function(id, category){
	$.ajax({type:"PUT",
		url: base_url + "/reuse/"+category+"/"+id});
	}


    var load_cat_edits = function(){
	
	get_all_cats.done(function(data2){
	    cat_edits = [];
	    all_cats = "";
	    for(var i = 0; i < data2.length; i++){
		all_cats += "<option value="+data2[i].categoryId+" class='cat_option'>";
		all_cats += data2[i].categoryName;
		all_cats += "</option>";
	    }
	    
	    $.ajax({type:"GET",
		    url: base_url+"/reuse_busi_cats/"+$("#hidden_busi").val(),
		    dataType: 'json',
		    success:function(data){
			var cats = "";
			console.log(data);
			$("#existing_cats").html(cats);
			for(var i = 0; i < data.length; i++){
			    cats = "";
			    cats += "<div id=cat_group><select name='edit_cat' class='edit-cat'id='edit_cat"+i+"' >";
			    cats += all_cats;
			    cats += "</select><input type = 'button' class='button remove_cat' value=X></div>";
			    $("#existing_cats").append(cats);
			    $('#edit_cat'+i).val(data[i].categoryId);
			    cat_edits.push(data[i].categoryId);
			}
			delete_cat();
		    }
		   });
	});
    }

    var select_change = function(){ 
	$("#select_cat").bind('change', function(){
	    var selected = $("option:selected", this);
	    $.ajax({type:"GET",
		    dataType: 'json',
		    url: base_url+"/reuse/"+selected.val(),
		    success: function(data){
			$("#hidden_busi").val(data[0].reuseId);
			$("#ebname").val(data[0].reuseName);
			$("#eaddress").val(data[0].reuseAddress);
			$("#ecity").val(data[0].reuseCity);
			$("#estate").val(data[0].reuseState);
			$("#ezip").val(data[0].reuseZip);
			$("#ephone").val(data[0].reusePhone);
			$("#ewebsite").val(data[0].reuseWeb);
			$("#ehours").val(data[0].reuseHours);
			load_cat_edits();
		    }
		   });
	});
    }

    var load_business = function (){
	get_businesses.done(function(data){
		    var busis = "<select name='select_cat' id='select_cat'><option>---------------</option>";
		    var blist = "";
		    for(var i = 0; i < data.length; i++){
			busis += "<option value=";
			busis += data[i].reuseId + ">";
			busis += data[i].reuseName;
			busis += "</option>";
			blist += "<div class='busi-wrapper'>";
			blist += "<span class='lname'>" + data[i].reuseName + "</span>";
			if(data[i].reuseAddress)
			    blist += "<span class='laddress'>" + data[i].reuseAddress + "</span>";
			if(data[i].reuseCity)
			    blist += "<span class='lcity'>" + data[i].reuseCity + ", </span>";
			if(data[i].reuseState)
			    blist += "<span class ='lstate'>" + data[i].reuseState + " </span>";
			if(data[i].reuseZip)
			    blist += "<span class ='lzip'>" + data[i].reuseZip + "</span>";
			if(data[i].reusePhone)
			    blist += "<span class ='lphone'>" + data[i].reusePhone + "</span>";
			if(data[i].reuseWeb)
			    blist += "<span class ='lweb'>" + data[i].reuseWeb + "</span>";
			blist += "</div>";
		    }
	    busis += "</select>";
	    $("#cur_busis").html(busis);
	    $("#busi_list").html(blist);
	    $("#ebname").val("");
	    $("#eaddress").val("");
	    $("#ecity").val("");
	    $("#estate").val("");
	    $("#ezip").val("");
	    $("#ephone").val("");
	    $("#ewebsite").val("");
	    $("#ehours").val("");
	    $("#hidden_busi").val("");
	    select_change();
	});
    }					       
			      
		    
    load_business();
    add_cats();

    $("#edit_more_cats").click(function(){
	var cats = "";
	cats += "<div id=cat_group><select name='edit_cat' class='edit-cat'>";
	cats += all_cats;
	cats += "</select><input type = 'button' class='button remove_cat' value=X></div>";
	$('#existing_cats').append(cats);
	delete_cat();
    });
			      
			    
	    


    $("#add_busi").click(function(){
	var name = document.getElementById("bname").value;
	var address = document.getElementById("address").value;
	var city = document.getElementById("city").value;
	var state = document.getElementById("state").value;
	var zip = document.getElementById("zip").value;
	var phone = document.getElementById("phone").value;
	var web = document.getElementById("website").value;
	var hours = document.getElementById("hours").value;

	$.ajax({type:"POST",
		url: base_url+"/reuse",
		contentType: 'application/json',
		data:JSON.stringify({'reuseName':name,
				     'reuseAddress': address,
				     'city': city,
				     'state': state,
				     'zip': zip,
				     'phone': phone,
				     'web': web,
				     'hours': hours})
		}).always(function(data){
		    var busi_id = data;
		    $('.add-cat').each(function(){
			var cat = $('option:selected', this).val();
			$.ajax({type: "PUT",
				url : base_url + "/reuse/"+cat+"/" + busi_id
			       }).done(function(){
				   load_business();
			       });
		    });
				      
		});
	});

    $("#del_busi").click(function(){
	var busi_id = document.getElementById("hidden_busi").value;
	var busi_name = document.getElementById("ebname").value;
	var cont = confirm("Are you sure you want to delete " + busi_name);
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url + "/reuse/" + busi_id,
		    dataType: 'json'
		   }).done(function(){
		       load_business();
		   });
	}
    });

    $("#edit_busi").click(function(){
	var name = document.getElementById("ebname").value;
	var address = document.getElementById("eaddress").value;
	var city = document.getElementById("ecity").value;
	var state = document.getElementById("estate").value;
	var zip = document.getElementById("ezip").value;
	var phone = document.getElementById("ephone").value;
	var web = document.getElementById("ewebsite").value;
	var hours = document.getElementById("ehours").value;
	var busi_id = document.getElementById("hidden_busi").value;
	$.ajax({type:"PATCH",
		url: base_url + "/reuse/" + busi_id,
		contentType: 'application/json',
		data:JSON.stringify({'businessName':name,
				     'Address': address,
				     'city': city,
				     'state': state,
				     'zip': zip,
				     'phone': phone,
				     'website': web,
				     'hours': hours})
	       }).always(function(){
		   //DELETE existing categories use array cat_edits
		   $.each(cat_edits, function(i , val){
		       delete_assoc(busi_id, val);
		   });
		   
		   $(".edit-cat").each(function(){
		       var val = $('option:selected', this).val();
		       //REMOVE console.log PUT all categories
		       create_assoc(busi_id, val);
		   });
		   load_business();
		
	       });
    });
	
    $("#more_cats").click(function(){
	add_cats();
    });
});
