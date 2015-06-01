<?php
   include 'check_token.php';
   echo check_token();

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Corvallis Reuse and Repair Directory Admin-Site</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="corvallis.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  </head>
  
  <body>
    <div id="header-wrap">
      <div id="logo"></div>
      <h2>Corvallis Reuse and Repair Directory</h2>
      <h1>Administrative Site</h1>
    </div>
    <div id="menu-wrap">
      <div class="sub-menu-wrap">
	<ul>
	  <li class="menu-header">Reuse</li>
	  <li>
	    <ul class="sub-menu">
	      <li><a class="link" href="./reuse_businesses.html">Businesses</a></li>
	      <li><a class="link" href="./reuse_categories.html">Categories</a></li>
	      <li><a class="link" href="./reuse_items.html">Items</a></li>
	    </ul>
	  </li>
	</ul>
      </div>
      <div class="sub-menu-wrap">
	<ul>
	  <li class="menu-header">Repair</li>
	  <li>
	    <ul class="sub-menu">
	      <li><a class="link" href="./rep_businesses.html">Businesses</a></li>
	      <li><a class="link" href="./rep_items.html">Items</a></li>
	    </ul>
	  </li>
	</ul>
      </div>
    </div>


  </body>
</html>