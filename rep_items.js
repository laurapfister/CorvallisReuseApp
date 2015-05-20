var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";




$(document).ready(function(){

    function load_items(){$.ajax({type:"GET",
		url: base_url+"/repairItem",
		dataType: 'json',
		success: function(data){
		    var items = "<select name='select_item' id='select_item'><option>-------------</option>";
		    var list = "";
		    for(var i = 0; i < data.length; i++){
			console.log(data[i].itemName);
			items += "<option>";
			items += data[i].itemName;
			items += "</option>";
			list += "<div class='item'>";
			list += data[i].itemName;
			list += "</div>";
		    }
		    items += "</select>";
		    document.getElementById("cur_items").innerHTML = items;
		    document.getElementById("item_list").innerHTML = list;
		    
		    var selected = $("option:selected", this);
			document.getElementById("item_name_edit").value = selected.text();
			document.getElementById("hidden_select").value = selected.text();

		    $("#select_item").bind('change', function(){
			var selected = $("option:selected", this);
			document.getElementById("item_name_edit").value = selected.text();
			document.getElementById("hidden_select").value = selected.text();
		    });
		},
		error: function(){
		    console.log("error");
	       }
	   });
	}
    load_items();


    $("#edit_item").click(function(){
	var cur_item = document.getElementById("hidden_select").value;
	var new_item = document.getElementById("item_name_edit").value;
	$.ajax({type:"PATCH",
		url: base_url+"/repairItem/"+cur_item+"/"+new_item,
		dataType: 'json'
		}).always(function(){
		   load_items();
		   });
		    
    });

    $("#add_item").click(function(){
	var new_item = document.getElementById("iname").value;
	$.ajax({type:"POST",
		url:base_url+"/repairItem",
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'itemName': new_item})
	       }).always(function(){
		   load_items();
		   document.getElementById("iname").value = "";
		   });
    });

    $("#delete_item").click(function(){
	var cur_item = document.getElementById("hidden_select").value;
	var cont = confirm("Are you sure you want to delete "+cur_item+"?");
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

