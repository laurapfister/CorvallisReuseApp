var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";

$(document).ready(function(){
    function add_items(){
	$.ajax({type:"GET",
		url: base_url+"/repairItem",
		dataType: 'json',
		success: function(data){
		    var items = "<select name='select_item' class='add-item' >";
		    for(var i = 0; i < data.length; i++){
			items += "<option class='item_option'>";
			items += data[i].itemName;
			items += "</option>";
		    }
		    items += "</select>";
		    $("#add_items").append(items);
		}
	       });
    }

	
	
    function load_businesses(){
	$.ajax({type:"GET",
		url: base_url+"/repair",
		dataType: 'json',
		success: function(data){
		    var busis = "<select name='select_item' id='select_item'><option>---------------</option>";
		    var blist = "";
		    for(var i = 0; i < data.length; i++){
			busis += "<option value=";
			busis += data[i].repairId + ">";
			busis += data[i].repairName;
			busis += "</option>";
			blist += "<div class='busi-wrapper'>";
			blist += "<span class='lname'>" + data[i].repairName + "</span>";
			blist += "<span class='laddress'>" + data[i].repairAddress + "</span>";
			blist += "<span class='lcity'>" + data[i].repairCity + ", </span>";
			blist += "<span class ='lstate'>" + data[i].repairState + " </span>";
			blist += "<span class ='lzip'>" + data[i].repairZip + "</span>";
			blist += "<span class ='lphone'>" + data[i].repairPhone + "</span>";
			if(data[i].repairWeb)
			    blist += "<span class ='lweb'>" + data[i].repairWeb + "</span>";
			blist += "</div>";
		    }
		    busis += "</select>";
		    document.getElementById("cur_busis").innerHTML = busis;
		    document.getElementById("busi_list").innerHTML = blist;
		    
		    $("#select_item").bind('change', function(){
			var selected = $("option:selected", this);
			$.ajax({type:"GET",
				dataType: 'json',
				url: base_url+"/repair/"+selected.val(),
				success: function(data){
				    $("#hidden_busi").val(data[0].repairId);
				    $("#ebname").val(data[0].repairName);
				    $("#eaddress").val(data[0].repairAddress);
				    $("#ecity").val(data[0].repairCity);
				    $("#estate").val(data[0].repairState);
				    $("#ezip").val(data[0].repairZip);
				    $("#ephone").val(data[0].repairPhone);
				    $("#ewebsite").val(data[0].repairWeb);
				    $("#ehours").val(data[0].repairHours);
				    $("#eaddInfo").val(data[0].repairAddInfo);

				    $.ajax({type:"GET",
					    url: base_url+"/repair_busi_items/"+$("#hidden_busi").val(),
					    dataType: 'json',
					    success:function(data){
						var items = "";
						for(var i = 0; i < data.length; i++){
						    items += "<select name='edit_item' class='add-item' >";
						    items += $('.select_item').text();
						    $('option:selected', this).val(data[i].itemName);
						}
						items += "</select>";
						document.getElementById("existing_items").innerHTML = items;
					    }
					   });

				}
			       
			       });
		    });
		}
		});
	}
			      
		    
    load_businesses();
    add_items();

    $("#add_busi").click(function(){
	var name = document.getElementById("bname").value;
	var address = document.getElementById("address").value;
	var city = document.getElementById("city").value;
	var state = document.getElementById("state").value;
	var zip = document.getElementById("zip").value;
	var phone = document.getElementById("phone").value;
	var web = document.getElementById("website").value;
	var hours = document.getElementById("hours").value;
	var addInfo = document.getElementById("addInfo").value;

	$.ajax({type:"POST",
		url: base_url+"/repair",
		contentType: 'application/json',
		data:JSON.stringify({'businessName':name,
				     'Address': address,
				     'city': city,
				     'state': state,
				     'zip': zip,
				     'phone': phone,
				     'website': web,
				     'hours': hours,
				     'addInfo': addInfo})
		}).always(function(data){
		    var busi_id = data;
		    $('.add-item').each(function(){
			var item = $('option:selected', this).text();
			$.ajax({type: "PUT",
				url : base_url + "/repair/"+busi_id+"/" + item
			       });
			});
		    load_businesses();
		    });
	});

    $("#del_busi").click(function(){
	var busi_id = document.getElementById("hidden_busi").value;
	var busi_name = document.getElementById("ebname").value;
	var cont = confirm("Are you sure you want to delete " + busi_name);
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url + "/repair/" + busi_id,
		    dataType: 'json'
		   }).always(function(){
		       load_businesses();
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
	var addInfo = document.getElementById("eaddInfo").value;
	var busi_id = document.getElementById("hidden_busi").value;
	$.ajax({type:"PATCH",
		url: base_url + "/repair/" + busi_id,
		contentType: 'application/json',
		data:JSON.stringify({'businessName':name,
				     'Address': address,
				     'city': city,
				     'state': state,
				     'zip': zip,
				     'phone': phone,
				     'website': web,
				     'hours': hours,
				     'addInfo': addInfo})
		}).always(function(){
		    load_businesses();
		});
	});
	
    $("#more_items").click(function(){
	add_items();
    });
});
