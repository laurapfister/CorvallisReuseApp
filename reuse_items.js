/*Javascript for reuse_item.php*/
var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";




$(document).ready(function(){

    var all_cats = "";


    /*Ajax call to get all categories*/
     var get_all_cats = $.ajax({type:"GET",
				dataType:"json",
				url: base_url+"/reuseCategory"});

    /*Gets all categories and stores them in variable all_cats, to increase efficiency*/
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
				      
    /*Gets all Items and adds them to both the bottom list and the edit list
      Also creates event handler for drop-down edit button
     */
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
		   $("#cur_items").html(items);
		   $("#item_list").html(list);
		    
		    var selected = $("option:selected", this);
			$("#item_name_edit").val(selected.text());
			$("#hidden_select").val(selected.val());

		    $("#select_item").bind('change', function(){
			var selected = $("option:selected", this);
			$("#item_name_edit").val(data[selected.val()].itemName);
			$("#hidden_select").val(data[selected.val()].itemId);
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


    /*After editing, patches selected item with new inputs upon clicking "Save Edit"*/
    $("#edit_item").click(function(){
	var cur_item = $("#hidden_select").val();
	var new_item = $("#item_name_edit").val();
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

    /*Adds new item to the database when "Add Item" button is clicked*/
    $("#add_item").click(function(){
	var new_item = $("#iname").val();
	var cat = $("option:selected", '#add_cats').val();
	$.ajax({type:"POST",
		url:base_url+"/reuseItems",
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'itemName': new_item, 
				     'category': cat})
	       }).always(function(){
		   load_items();
		   $("#iname").val("");
		   });
    });

    /*Deletes selected item when "Delete Item is clicked, only deletes if user confirms deletion*/
    $("#delete_item").click(function(){
	var item_name = $("#item_name_edit").val();
	var cur_item = $("#hidden_select").val();
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

