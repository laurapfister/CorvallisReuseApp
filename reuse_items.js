var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";




$(document).ready(function(){

    var all_cats = "";

     var get_all_cats = $.ajax({type:"GET",
				dataType:"json",
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
	    all_cats = cats;
	    $("#add_cats").html(cats);
	});
    }
				      

    function load_items(){$.ajax({type:"GET",
		url: base_url+"/reuseItems",
		dataType: 'json',
		success: function(data){
		    var items = "<select name='select_item' id='select_item'><option>-------------</option>";
		    var list = "";
		    for(var i = 0; i < data.length; i++){
			items += "<option value = " + i + ">";
			items += data[i].itemName + " ( "+data[i].categoryName+")";
			items += "</option>";
			list += "<div class='item'>";
			list += "<span class='item-sep'>"+data[i].itemName +"</span>"+ "<span class='cat-sep'>("+data[i].categoryName+")</span>";
			list += "</div>";
		    }
		    items += "</select>";
		    document.getElementById("cur_items").innerHTML = items;
		    document.getElementById("item_list").innerHTML = list;
		    
		    var selected = $("option:selected", this);
			document.getElementById("item_name_edit").value = selected.text();
			document.getElementById("hidden_select").value = selected.val();

		    $("#select_item").bind('change', function(){
			var selected = $("option:selected", this);
			document.getElementById("item_name_edit").value = data[selected.val()].itemName;
			document.getElementById("hidden_select").value = data[selected.val()].itemId;
			$("#existing_cats").html(all_cats);
			$("#existing_cats").children().val(data[selected.val()].categoryId);
		
		    });
		},
		error: function(){
		    console.log("error");
	       }
	   });
	}
    add_cats();
    load_items();


    $("#edit_item").click(function(){
	var cur_item = document.getElementById("hidden_select").value;
	var new_item = document.getElementById("item_name_edit").value;
	var category = $("#existing_cats").children().val();
	$.ajax({type:"PATCH",
		url: base_url+"/reuseItems/"+cur_item,
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'itemName': new_item,
				     'category': category})
		}).always(function(){
		   load_items();
		   });
		    
    });

    $("#add_item").click(function(){
	var new_item = document.getElementById("iname").value;
	var cat = $("option:selected", '#add_cats').val();
	$.ajax({type:"POST",
		url:base_url+"/reuseItems",
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'itemName': new_item, 
				     'category': cat})
	       }).always(function(){
		   load_items();
		   document.getElementById("iname").value = "";
		   });
    });

    $("#delete_item").click(function(){
	var item_name = document.getElementById("item_name_edit").value;
	var cur_item = document.getElementById("hidden_select").value;
	var cont = confirm("Are you sure you want to delete "+item_name+"?");
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url+"/reuseItems/"+cur_item,
		    dataType: 'json',
		    success: function(){
			load_items();
		    }
		   }).always(load_items);
	    }
    });
});
