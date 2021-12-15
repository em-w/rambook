<?php
	
	$name = $desc = $agreement = $connection = $grade = "";
	$nameErr = $descErr = $agreeErr = $connErr = $pfpErr = "";
	
	$error = false;	
	
	$file = "userprofiles.json"; // json file for storing user data
	$targetDir = "profileimages/"; // directory for storing pfps
	$uid = 0;
	
	include "createthumbnail.php";

	// if form was submitted, validate data
	if ($_SERVER["REQUEST_METHOD"] === "POST") {

		if (empty($_POST["name"])) {
			$nameErr = "Name required.";
			$error = true;
		} else {
			$name = format_input($_POST["name"]);
			if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
				$nameErr = "Letters and whitespace only, please.";
				$error = true;
			}
		} // else	
		
		if (empty($_POST["desc"])) {
			$descErr = "Description required.";
			$error = true;
		} else {
			$desc = format_input($_POST["desc"]);
		} // else
		
		if (empty($_POST["connection"])) {
			$connErr = "Please select an option.";
			$error = true;
		} else {
			$connection = $_POST["connection"];
			if ($_POST["connection"] == "student") {
				$grade = $_POST["grade"];
			} else {
				$_POST["grade"] = "NA";
			}
		} // else
		
		if (empty($_POST["agreement"])) {
			$agreeErr = "Please check.";
			$error = true;
		} else {
			$agreement = $_POST["agreement"];
		} // else
		
		if (empty($_FILES["pfp"]["name"])) {
			$pfpErr = "Please upload a profile photo.";
			$error = true;
		} else {
			// setting file-related variables if file is uploaded
			$uid = file_get_contents("identifier.txt");
			$targetFile = $targetDir . basename($_FILES["pfp"]["name"]);
			$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
			
			// rename target file to uid
			$targetFile = $targetDir . $uid . "." . $imageFileType;

			// check if file is an image
			$check = exif_imagetype($_FILES["pfp"]["tmp_name"]);
			
			if (!($check !== false)) {
				$pfpErr = "File is not an image.";
				$error = true;
				
			// check if file already exists
			} else if (file_exists($targetFile)) {
				$pfpErr = "Image already exists.";
				$error = true;
				
			// check if file is too large
			} else if ($_FILES["pfp"]["size"] > 4000000) {
				$pfpErr = "Sorry, your file is too large. All files must be under 4MB.";
				$error = true;
				
			// check if file is a valid image type
			} else if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
				$pfpErr = "Sorry, only .jpg, .jpeg, .png, and .gif files are allowed.";
				$error = true;
				
			}
		} // else
		
	} // if
	
	include "header.inc";	
	
	// if form was submitted successfully
	if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error) {	
		
		$_POST["uid"] = $uid;
		$_POST["imagetype"] = $imageFileType;
		write_data_to_file($file);
		upload_pfp($targetDir, $targetFile);
		
		file_put_contents("identifier.txt", ($uid + 1));
		if (!is_dir("thumbnails/")) {
			mkdir("thumbnails/", 0755);
		}
		$dest = "thumbnails/" . $uid . "." . $imageFileType;
		
		if (!file_exists($dest)) {
			createThumbnail($targetFile, $dest, 200, 200);	
		}
		
		include "home.inc";
		
	} else if ($error || (isset($_GET["page"]) && $_GET["page"] == "form")) {
		include "form.inc";
	} else {
		include "home.inc";
	} // else
	
	if (isset($_GET["action"]) && $_GET["action"] == "del") {
		if (file_exists($file)) {
			unlink($file);
		}
		if (is_dir($targetDir)) {
			delete_images($targetDir);
		}
		if (is_dir("thumbnails/")) {
			delete_images("thumbnails/");
		}
		file_put_contents("identifier.txt", 1);
		
	} // if

	include "footer.inc";

	function format_input($input) {
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	} // format_input
	 
	function write_data_to_file($file) {
		if (file_exists($file)) {
			$jsonstring = file_get_contents($file);
			
			// decode json string into php array
			$userprofiles = json_decode($jsonstring, true);
		}
		
		// add form submission to data
		$userprofiles[] = $_POST;
		
		// encode php array to formatted json
		$jsoncode = json_encode($userprofiles, JSON_PRETTY_PRINT);
		
		// write json to file
		file_put_contents($file, $jsoncode);
	} // write_data_to_file
	
	function upload_pfp($targetDir, $targetFile) {
		// if targetDir doesn't exist, create it
		if (!is_dir($targetDir)) {
			mkdir($targetDir, 0755);
		}
		
		// upload the image file
		move_uploaded_file($_FILES["pfp"]["tmp_name"], $targetFile);
		
		// replace the uploaded files with resized ones, if needed..?
		createThumbnail($targetFile, $targetFile, 500, 500);
	}
	
	function delete_images($dir) {
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($tempfile = readdir($dh)) !== false) {
					if (!($tempfile === ".." || $tempfile === ".")) {
						unlink($dir . $tempfile);
					}
				}
				closedir($dh);
			}
		}
		
	}
?>