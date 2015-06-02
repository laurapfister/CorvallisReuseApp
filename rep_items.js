var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";




$(document).ready(function(){

    /*Loads all repair items to list at bottom, and edit dropdown menu
      Also adds event handler to drop down selection, to populate edit field
     */
    function load_items(){$.ajax({type:"GET",
		url: base_url+"/repairItem",
		dataType: 'json',
		success: function(data){
		    var items = "<select name='select_item' id='select_item'><option>-------------</option>";
		    var list = "";
		    for(var i = 0; i < data.length; i++){
			items += "<option value = " + data[i].itemId + ">";
			items += data[i].itemName;
			items += "</option>";
			list += "<div class='item'>";
			list += data[i].itemName;
			list += "</div>";
		    }
		    items += "</select>";
		    $("#cur_items").html(items);
		    $("#item_list").html(list);
		    
		    var selected = $("option:selected", this);
			$("#item_name_edit").val(selected.text());
			$("#hidden_select").val(selected.val());

		    $("#select_item").bind('change', function(){
			var selected = $("option:selected", this);
			$("#item_name_edit").val(selected.text());
			$("#hidden_select").val(selected.val());
		    });
		},
		error: function(){
		    console.log("error");
	       }
	   });
	}
    load_items();

    /*Adds changes to selected item when "Save Edit" is pushed*/
    $("#edit_item").click(function(){
	var cur_item = $("#hidden_select").val();
	var new_item = $("#item_name_edit").val();
	$.ajax({type:"PATCH",
		url: base_url+"/repairItem/"+cur_item+"/"+new_item,
		dataType: 'json'
		}).always(function(){
		   load_items();
		   });
		    
    });
    
    /*Adds new item to database*/
    $("#add_item").click(function(){
	var new_item = $("#iname").val();
	$.ajax({type:"POST",
		url:base_url+"/repairItem",
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'itemName': new_item})
	       }).always(function(){
		   load_items();
		   $("#iname").val("");
		   });
    });

    /*Deletes selected item from database, but only after user confirmation*/
    $("#delete_item").click(function(){
	var item_name = $("#item_name_edit").val();
	var cur_item = $("#hidden_select").val();
	var cont = confirm("Are you sure you want to delete "+item_name+"?");
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url+"/repairItem/"+cur_item,
		    dataType: 'json',
		    success: function(){
			load_items();
		    }
		   }).always(load_items);
	    }
    });
});

