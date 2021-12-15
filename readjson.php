<?php

 // read json file into array of strings
 $jsonstring = file_get_contents("userprofiles.json");
 
 // save the json data as a PHP array
 $phparray = json_decode($jsonstring, true);
 
 // use GET to determine type of access
 if (isset($_GET["access"])){
  $access = $_GET["access"];
 } else {
  $access = "all"; 
 }
 
  // pull student, alumnus or staff only or return all
  $returnData = [];
  if ($access != "all") { 
   foreach($phparray as $entry) {
    // var_dump($entry);
      if ($entry["connection"] == $access) {
         $returnData[] = $entry;  
      }      
   } // foreach
  } else {
     $returnData = $phparray;
  }

// encode the php array to json 
 $jsoncode = json_encode($returnData, JSON_PRETTY_PRINT);
 echo ($jsoncode);



?>