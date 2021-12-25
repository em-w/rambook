<?php
	session_start();
?>
<!<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>rambook</title>
    
    <!-- include an external style sheet -->
    <link rel="stylesheet" href="style.css">  
    
  </head>
  
  <body>
  
  <h1>Welcome to RamBook!</h1>
  <a href="?page=login">log in</a>&nbsp;&nbsp;<a href="?page=signup">sign up</a>
  <ul>
	<li><a href="?page=home">view profiles</a></li>
	<li><a href="?page=form">add profile</a></li>
	<li><a href="?action=del">reset gallery</a></li>
	<li><a href="downloadall.php">download all</a></li>
  </ul>
  <br>
  
