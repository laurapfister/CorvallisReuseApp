<?php
	/*Checks if token exists, and is valid. Otherwise redirects to login page*/
  	include 'check_token.php';
	check_token();
	
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Repair Businesses Directory Edit</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="rep_businesses.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  </head>
  <body>
    <!--- Header for the website here. Include Link to home page and to other
	  pages--->
    <div id="header">
      <div id="logo"></div>
      <div id="heading">Repair Businesses Directory</div>
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
    <!--- New Business input here --->
    <section>
      <h3>Create New Business</h3>
      <div class="body-wrap">
	<div class="input-wrap">
	  <form>
	    <label>Business Name<input class="full_line" id="bname" type="text"></label>
	    <label>Address<input class="full_line" id="address" type="text"></label>
	    <label>City<input class="partial_line" id="city" type="text"></label>
	    <label>State<input class="partial_line" id="state" type="text"></label>
	    <label>Zip<input class="partial_line" id="zip" type="text"></label>
	    <BR>
	    <label>Phone Number<input class="partial_line" id="phone" type="text"></label>
	    
	    <label>Website<input class="full_line" id="website" type="text"></label>
	    <label>Hours<textarea id="hours" rows="4" type="text"></textarea></label>
	    <label>Additional Info<textarea id="addInfo" rows="4" type="text"></textarea></label>
	    
	    <!--ADD items here --->
	    <input class="button" type="button" value="Add Business" id="add_busi">
	  </form>
	</div>
	<div class="input-wrap">
	  <label>Select Items this Business Repairs
	    <div id="add_items"></div>
	    <input class="button" type="button" value="+ Add Another Item" id="more_items">
	  </label>

	</div>
      </div>
    </section>
    
    <!--- Edit Existing Business here --->
    <section>
      <h3> Edit or Delete Existing Business </h3>
      <div class="body-wrap">
	<div class="input-wrap">
	  <form>
	    <input type="hidden" id="hidden_busi">
	    <label>Select Existing Business to Edit<div id="cur_busis"></div></label>
	    <label>Business Name<input class="full_line" id="ebname" type="text"></label>
	    <label>Address<input class="full_line" id="eaddress" type="text"></label>
	    <label>City<input class="partial_line" id="ecity" type="text"></label>
	    <label>State<input class="partial_line" id="estate" type="text"></label>
	    <label>Zip<input id="ezip" class="partial_line" type="text"></label>
	    <br>
	    <label>Phone Number<input id="ephone" type="text"></label>
	    <label>Website<input class="full_line" id="ewebsite" type="text"></label>
	    <label>Hours<textarea id="ehours" rows="4" type="text"></textarea></label>
	    <label>Additional Info<textarea rows="4"  id="eaddInfo" type="text"></textarea></label>
	    
	 
	    <input class="button" type="button" value="Save Edit" id="edit_busi">
	    <input class="button" type="button" value="Delete Business" id="del_busi">
	  </form>
	</div>
	   <!--ADD items here --->
	<div class="input-wrap">
	  <label>Items this Business Repairs
	    <div id="existing_items"></div>
	    <input class="button" type="button" value="+ Add Another Item" id="edit_more_items">
	  </label>
	</div>
      </div>
    </section>

    <!--- List of  Businesses here --->
    <section>
      <h3> Businesses </h3>
      <div class="body-wrap" id="busi_list">
      </div>
    </section>


    <script src="rep_businesses.js"></script>
    <script src="logout.js"></script>
  </body>
</html>
