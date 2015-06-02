var base_url = "http://web.engr.oregonstate.edu/~pfisterl/cs419/api/index.php";




$(document).ready(function(){

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
		    document.getElementById("cur_cats").innerHTML = cats;
		    document.getElementById("cat_list").innerHTML = list;
		    
		    var selected = $("option:selected", this);
			document.getElementById("cat_name_edit").value = selected.text();
			document.getElementById("hidden_select").value = selected.val();

		    $("#select_cat").bind('change', function(){
			var selected = $("option:selected", this);
			document.getElementById("cat_name_edit").value = selected.text();
			document.getElementById("hidden_select").value = selected.val();
		    });
		},
		error: function(){
		    console.log("error");
	       }
	   });
	}
    load_cats();


    $("#edit_cat").click(function(){
	var cur_cat = document.getElementById("hidden_select").value;
	var new_cat = document.getElementById("cat_name_edit").value;
	$.ajax({type:"PATCH",
		url: base_url+"/reuseCategory/"+cur_cat+"/"+new_cat,
		dataType: 'json'
		}).always(function(){
		   load_cats();
		   });
		    
    });

    $("#add_cat").click(function(){
	var new_cat = document.getElementById("iname").value;
	$.ajax({type:"POST",
		url:base_url+"/reuseCategory",
		dataType: 'json',
		contentType: 'application/json',
		data:JSON.stringify({'categoryName': new_cat})
	       }).always(function(){
		   load_cats();
		   document.getElementById("iname").value = "";
		   });
    });

    $("#delete_cat").click(function(){
	var cat_name = document.getElementById("cat_name_edit").value;
	var cur_cat = document.getElementById("hidden_select").value;
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

