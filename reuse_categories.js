/*Javascript page for reuse_categories.js*/
var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";




$(document).ready(function(){

    /*Gets all categories from database, adds them to Edit section, and bottom list
      Also creates event handler for drop down edit menu
    */
    function load_cats(){$.ajax({type:"GET",
		url: base_url+"/reuseCategory",
		dataType: 'json',
		success: function(data){
		    var cats = "<select name='select_cat' id='select_cat'><option>-------------</option>";
		    var list = "";
		    for(var i = 0; i < data.length; i++){
		
			cats += "<option value = " + data[i].categoryId + ">";
			cats += data[i].categoryName;
			cats += "</option>";
			list += "<div class='cat'>";
			list += data[i].categoryName;
			list += "</div>";
		    }
		    cats += "</select>";
		    $("#cur_cats").html(cats);
		    $("#cat_list").html(list);
		    
		    var selected = $("option:selected", this);
		    $("#cat_name_edit").val(selected.text());
		    $("#hidden_select").val(selected.val());

		    $("#select_cat").bind('change', function(){
			var selected = $("option:selected", this);
			$("#cat_name_edit").val(selected.text());
			$("#hidden_select").val(selected.val());
		    });
		},
		error: function(){
		    console.log("error");
	       }
	   });
	}
    load_cats();

    /*Event handler for "Save Edit" button. Saves the edits of the selected category to the database*/
    $("#edit_cat").click(function(){
	var cur_cat = $("#hidden_select").val();
	var new_cat = $("#cat_name_edit").val();
	$.ajax({type:"PATCH",
		url: base_url+"/reuseCategory/"+cur_cat+"/"+new_cat,
		dataType: 'json'
		}).always(function(){
		   load_cats();
		   });
		    
    });
    /*Event Handler for "Add Category" button. Adds the new category name to the database*/
    $("#add_cat").click(function(){
	var new_cat = $("#iname").val();
	$.ajax({type:"POST",
		url:base_url+"/reuseCategory",
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'categoryName': new_cat})
	       }).always(function(){
		   load_cats();
		   $("#iname").val("");
		   });
    });
    /*Event handler for "Delete Category" button. Deletes the selected category, but only have user confirmation*/
    $("#delete_cat").click(function(){
	var cat_name = $("#cat_name_edit").val();
	var cur_cat = $("#hidden_select").val();
	var cont = confirm("Are you sure you want to delete "+cat_name+"?");
	if(cont){
	    $.ajax({type:"DELETE",
		    url: base_url+"/reuseCategory/"+cur_cat,
		    dataType: 'json',
		    success: function(){
			load_cats();
		    }
		   }).always(load_cats);
	    }
    });
});

