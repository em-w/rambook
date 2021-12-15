<?php

 // read json file into array of strings
 $jsonstring = file_get_contents("userprofiles.json");
 
 // save the json data as a PHP array
 $phparray = json_decode($jsonstring, true);
 
 // use GET to determine type of access
 if (isset($_GET["term"])){
    $searchterms = explode("%", $_GET["term"]);
   } else {
    $searchterms = ""; 
   }
 
  // pull student, alumnus or staff only or return all
  $returnData = [];
  if ($searchterms != "") { 
    foreach($phparray as $entry) {
        foreach($searchterms as $term) {
            if (str_contains($entry["name"], $term)) {
                $returnData[] = $entry;
                break;
            } else if (str_contains($entry["desc"], $term)) {
                $returnData[] = $entry;
                break;
            }
        } 
    } // if 
  } else {
      // return all profiles if get array is not properly set because I'm not sure what else to do...?
     $returnData = $phparray;
  }

// encode the php array to json 
 $jsoncode = json_encode($returnData, JSON_PRETTY_PRINT);
 echo ($jsoncode);



?>