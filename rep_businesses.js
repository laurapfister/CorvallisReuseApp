var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";

$(document).ready(function(){
    

    var item_edits = [];
    var all_items = "";
    var items_new_busi = "";

    /*Ajax call to get all items from database*/
    var get_all_items = $.ajax({type:"GET",
				data:"json",
				url: base_url+"/repairItem"});
	
    
    /*Adds all items to dropdown list in create new business section*/
    function add_items(){
	get_all_items.done(function(data){
	    var items = "<select name='select_item' class='add-item' >";
	    for(var i = 0; i < data.length; i++){
		items += "<option class='item_option' value=" +data[i].itemId +">";
		items += data[i].itemName;
		items += "</option>";
	    }
	    items += "</select>";
	    $("#add_items").append(items);
	    items_new_busi = items;
	});
    }
	

    /*Ajax call to get all repair businesses from database*/
    var get_businesses = $.ajax({type:"GET",
				 url: base_url+"/repair",
				 dataType: 'json'
				});


    /*Ajax call to remove item from Items this Business Repairs*/
    var delete_item = function(){
	$(".remove_item").bind('click', function(){
	    $(this).parent().remove();
	});
    }

    /*Deletes the association between an item and business*/
    var delete_assoc = function(id, item){
	$.ajax({type:"DELETE",
		url: base_url + "/repair/"+id+"/"+item,
	      async: false})
	}

    /*Creates association between item and business*/
    var create_assoc = function(id, item){
	$.ajax({type:"PUT",
		url: base_url + "/repair/"+id+"/"+item,
	       async: false});
	}


    /*Gets all items associated with selected business and adds them to Items this Business repairs*/
    var load_item_edits = function(){
	
	get_all_items.done(function(data2){
	    item_edits = [];
	    all_items = "";
	    for(var i = 0; i < data2.length; i++){
		all_items += "<option value='"+data2[i].itemId+"' class='item_option'>";
		all_items += data2[i].itemName;
		all_items += "</option>";
	    }
	    
	    $.ajax({type:"GET",
		    url: base_url+"/repair_busi_items/"+$("#hidden_busi").val(),
		    dataType: 'json',
		    success:function(data){
			var items = "";
			$("#existing_items").html(items);
			for(var i = 0; i < data.length; i++){
			    items = "";
			    items += "<div id=item_group><select name='edit_item' class='edit-item'id='edit_item"+i+"' >";
			    items += all_items;
			    items += "</select><input type = 'button' class='button remove_item' value=X></div>";
			    $("#existing_items").append(items);
			    $('#edit_item'+i).val(data[i].itemId);
			    item_edits.push(data[i].itemId);
			}
			delete_item();
		    }
		   });
	});
    }

    /*Populates edit fields when business is selected from dropdown menu*/
    var select_change = function(){ 
	$("#select_item").bind('change', function(){
	    var selected = $("option:selected", this);
	    $('#existing_items').html("");
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
			load_item_edits();
		    }
		   });
	});
    }

    /*Adds all existing businesses to drop down select menu in edit exisitng businesses section*/
    var load_business = function (){
	get_businesses.done(function(data){
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
	    $("#cur_busis").html(busis);
	    $("#busi_list").html(blist);
	    /*$("#ebname").val("");
	    $("#eaddress").val("");
	    $("#ecity").val("");
	    $("#estate").val("");
	    $("#ezip").val("");
	    $("#ephone").val("");
	    $("#ewebsite").val("");
	    $("#ehours").val("");
	    $("#eaddInfo").val("");
	    $("#hidden_busi").val("");*/
	    select_change();
	});
    }					       
			      
		    
    load_business();
    add_items();

    /*Adds new drop down menu of items to "Items this Business Repairs"*/
    $("#edit_more_items").click(function(){
	var items = "";
	items += "<div id=item_group><select name='edit_item' class='edit-item'>";
	items += all_items;
	items += "</select><input type = 'button' class='button remove_item' value=X></div>";
	$('#existing_items').append(items);
	delete_item();
    });
			      
			    
	    

    /*Adds new business to database*/
    $("#add_busi").click(function(){
	var name = $("#bname").val();
	var address = $("#address").val();
	var city = $("#city").val();
	var state = $("#state").val();
	var zip = $("#zip").val();
	var phone = $("#phone").val();
	var web = $("#website").val();
	var hours = $("#hours").val();
	var addInfo = $("#addInfo").val();

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
			var item = $('option:selected', this).val();
			$.ajax({type: "PUT",
				url : base_url + "/repair/"+busi_id+"/" + item
			       });
			});
		      location.reload();
		});
	});

    /*Deletes selected business from database, only after user confirmation*/
    $("#del_busi").click(function(){
	var busi_id = $("#hidden_busi").val();
	var busi_name = $("#ebname").val();
	var cont = confirm("Are you sure you want to delete " + busi_name);
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url + "/repair/" + busi_id,
		    dataType: 'json'
		   }).always(function(){
		       location.reload();
		   });
	}
    });

    /*Saves edits of business when "Save Edit" is clicked*/
    $("#edit_busi").click(function(){
	var name = $("#ebname").val();
	var address = $("#eaddress").val();
	var city = $("#ecity").val();
	var state = $("#estate").val();
	var zip = $("#ezip").val();
	var phone = $("#ephone").val();
	var web = $("#ewebsite").val();
	var hours = $("#ehours").val();
	var addInfo = $("#eaddInfo").val();
	var busi_id = $("#hidden_busi").val();
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
		   //DELETE existing items use array item_edits
		   $.each(item_edits, function(i , val){
		       delete_assoc(busi_id, val);
		   });
		   
		   $(".edit-item").each(function(){
		       var val = $('option:selected', this).val();
		       //REMOVE console.log PUT all items
		       create_assoc(busi_id, val);
		   });
		   location.reload();
	       });
    });

    /*Adds more items dropdown to create new business section*/
    $("#more_items").click(function(){
	add_items();
    });
});
