<?php
  	include 'check_token.php';
	check_token();
	
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Repair Items Directory Edit</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="rep_items.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  </head>
  <body>
    <div id="header">
      <div id="logo"></div>
      <div id="heading">Repair Items Directory</div>
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
    <div id="Items">
    <div>
      <section>
	<h3>Create New Item</h3>
	<form>
	  <label>Item Name<input id="iname" type="text"></label>
	  <input class="button" type="button" id="add_item" value = "Add Item">
	</form>
      </section>
    </div>
    <!--- Edit Existing item here --->
    <section>
      <h3> Edit or Delete Existing Item </h3>
       <form>
	 <label> Select Existing Item to Edit<div id="cur_items"></div></label>
	 <br>
	 <input type="hidden" id="hidden_select">
	 <label>Item Name<input id="item_name_edit" type="text"></label>
	 <input id="edit_item" class="button" type="button" value = "Save Edit">
	 <input id="delete_item" class="button" type="button" value = "Delete Item">
      </form>
    </section>

    <!--- List of  Items here --->
    <section>
      <h3> Items </h3>
      <div id="item_list"></div>
    </section>
    </div>

    <script src="rep_items.js"></script>
    <script src="logout.js"></script>
  </body>
</html>
