/*Javascript file for reuse_businesses.php*/
var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";

$(document).ready(function(){
    

    var cat_edits = [];
    var all_cats = "";
    
    /*Ajax call to get all categories from database*/
    var get_all_cats = $.ajax({type:"GET",
				data:"json",
				url: base_url+"/reuseCategory"});

    /*Gets all categories from database. Add them to Add Business section*/
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
	
    /*Ajax call to get all reuse businesses from database.*/
    var get_businesses = $.ajax({type:"GET",
				 url: base_url+"/reuse",
				 dataType: 'json'
				});


    /*Removes category from list of "Categories this Business Reuses*/
    var delete_cat = function(){
	$(".remove_cat").bind('click', function(){
	    $(this).parent().remove();
	});
    }

    /*Ajax call to remove association between category and business*/
    var delete_assoc = function(id, category){
	$.ajax({type:"DELETE",
		url: base_url + "/reuse/"+category+"/"+id,
	      async: false});
	}
    
    /*Ajax call to create association between category and business*/
    var create_assoc = function(id, category){
	$.ajax({type:"PUT",
		url: base_url + "/reuse/"+category+"/"+id,
	       async: false});
	}


    /*Loads associated categories to "Categories this Business Reuses" section*/
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

    /*Gets info from selected business and populates the Edit fields*/
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
    /*Creates list of businesses at bottom of page, and populates existing businesses dropdown field*/
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

    /*When "Add another Category" is selected it adds a new dropdown menu of categories*/
    $("#edit_more_cats").click(function(){
	var cats = "";
	cats += "<div id=cat_group><select name='edit_cat' class='edit-cat'>";
	cats += all_cats;
	cats += "</select><input type = 'button' class='button remove_cat' value=X></div>";
	$('#existing_cats').append(cats);
	delete_cat();
    });
			      
			    
	    

    /*Adds newly created business to the database*/
    $("#add_busi").click(function(){
	var name = $("#bname").val();
	var address = $("#address").val();
	var city = $("#city").val();
	var state = $("#state").val();
	var zip = $("#zip").val();
	var phone = $("#phone").val();
	var web = $("#website").val();
	var hours = $("#hours").val();

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
			       })
		    });
		    location.reload();
				      
		});
	});

    /*Deletes selected business from the database, only after use confirmation*/
    $("#del_busi").click(function(){
	var busi_id = $("#hidden_busi").val();
	var busi_name = $("#ebname").val();
	var cont = confirm("Are you sure you want to delete " + busi_name);
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url + "/reuse/" + busi_id,
		    dataType: 'json'
		   }).always(function(){
		         location.reload();
		   });
	}
    });
    /*Saves edits made to selected business when "Save Edit" button is clicked*/
    $("#edit_busi").click(function(){
	var name = $("#ebname").val();
	var address = $("#eaddress").val();
	var city = $("#ecity").val();
	var state = $("#estate").val();
	var zip = $("#ezip").val();
	var phone = $("#ephone").val();
	var web = $("#ewebsite").val();
	var hours = $("#ehours").val();
	var busi_id = $("#hidden_busi").val();
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
		   location.reload();
		
	       });
    });
    /*Adds event handler to new category sections, "Add More Categories
      Adds a new list of drop down categories upon clicking
     */
    $("#more_cats").click(function(){
	add_cats();
    });
});
