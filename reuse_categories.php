<?php
  	include 'check_token.php';
	check_token();
	
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Reuse Categories Directory Edit</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="reuse_categories.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  </head>
  <body>
    <!--- Header for the website here. Include Link to home page and to other
	  pages--->
    <div id="header">
      <div id="logo"></div>
      <div id="heading">Reuse Categories Directory</div>
      <div class="menu-wrap">
	<nav class="menu">
	  <ul class="menu-list">
	    <li><a href="CorvallisReuseRepair.php">Home</a></li>
	    <li>
	      <a href="#">Reuse<span class="drop-down"></span></a>
	      <ul class="sub-menu">
		<li><a href="./reuse_businesses.php">Businesses</a></li>
		<li><a href="./reuse_categories.php">Categories</a></li>
		<li><a href="./reuse_items.php">Items</a></li>
	      </ul>
	    </li>
	    <li>
	      <a href="#">Repair<span class="drop-down"></span></a>
	      <ul class="sub-menu">
		<li><a href="./rep_businesses.php">Businesses</a></li>
		<li><a href="./rep_items.php">Items</a></li>
	      </ul>
	    </li>
	  </ul>
	  <input type="button" class="button" id="logout" value="Logout">	
	</nav>

      </div>
    </div>

    <!--- New Item input here --->
    <div id="Categories">
      <div>
	<section>
	  <h3>Create New Category</h3>
	  <form>
	    <label>Category Name<input id="iname" type="text"></label>
	    <input type="button" id="add_cat" class="button" value = "Add Category">
	  </form>
	</section>
      </div>
      <!--- Edit Existing item here --->
      <section>
	<h3> Edit or Delete Existing Category </h3>
	<form>
	  <label> Select Existing Category to Edit<div id="cur_cats"></div></label>
	  <br>
	  <input type="hidden" id="hidden_select">
	  <label>Category Name<input id="cat_name_edit" type="text"></label>
	  <input type="button" class="button" id="edit_cat" value = "Save Edit">
	  <input type="button" class="button" id="delete_cat" value = "Delete Category">
	</form>
      </section>
      
      <!--- List of  Items here --->
      <section>
	<h3> Categories </h3>
	<div id="cat_list"></div>
	
      </section>
    </div>

    <script src="reuse_categories.js"></script>
    <script src="logout.js"></script>
  </body>
</html>
