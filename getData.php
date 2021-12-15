<?php
	$uid = $_GET["uid"];
	$file = "userprofiles.json";
	
	$jsonstring = file_get_contents($file);
	
	$userprofiles = json_decode($jsonstring, true);
	
	foreach ($userprofiles as $user) {
		if ($user["uid"] == $uid) {
			echo json_encode($user);
		}
	}
?>