<?php
session_start();

 // read json file into array of strings
 $jsonstring = file_get_contents("userprofiles.json");
 
 // save the json data as a PHP array
 $userarray = json_decode($jsonstring, true);

 $userstring = "";
 
 // use GET to determine type of access
 if (isset($_GET["access"])){
  $access = $_GET["access"];
 } else {
  $access = "all"; 
 }
 
  // pull student, alumnus or staff only or return all
  $returnData = [];
  if ($access != "all") { 
      if ($access == "self") {
         $userstring = file_get_contents($_SESSION["userUid"] . ".json");
         $returnData = json_decode($userstring);
      }

   /*foreach($phparray as $entry) {
      if ($entry["connection"] == $access) {
         $returnData[] = $entry;  
      }      
   } // foreach */

  } else {
      foreach ($userarray as $user) {
         $userstring = file_get_contents($user["uid"] . ".json");
         $userposts = json_decode($userstring, true);
         foreach ($userposts as $post) {
            $returnData[] = $post;
         } // this probably isn't the besk method, ask about merging arrays
      }
  }

// encode the php array to json 
 $jsoncode = json_encode($returnData, JSON_PRETTY_PRINT);
 echo ($jsoncode);



?>