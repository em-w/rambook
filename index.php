<?php

// temp
$credentials = [
    'username' => 'username', 
    'password' => 'password' 
];
	
	$name = $desc = $agreement = $connection = $grade = $username = $password = "";
	$nameErr = $descErr = $agreeErr = $connErr = $pfpErr = $userErr = $pwdErr = "";

	$uid = $imageFileType = "";

	$isPfpUploaded = "";
	
	$error = false;	
	
	$file = "userprofiles.json"; // json file for storing user data
	$targetDir = "profileimages/"; // directory for storing pfps
	$postDir = "postimages/"; // directory for storing posts
	$uid = 0;
	
	include "createthumbnail.php";

	include "header.inc";

	// if a form was submitted, validate data
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		
		// if login form is submitted
		if (isset($_POST["login"])) {
			$successful = false;
			if (file_exists($file)) {
				$jsonstring = file_get_contents($file);
				
				// decode json string into php array
				$userprofiles = json_decode($jsonstring, true);

				foreach ($userprofiles as $user) {
					if ($user["username"] == $_POST["username"] && $user["password"] == $_POST["password"]) {
						$_SESSION["loggedIn"] = 1;
						$_SESSION["userUid"] = $user["uid"];
						$_SESSION["userFile"] = $user["uid"] . ".json";
						$successful = true;
					}
				}
			}

			if (!$successful) {
				echo "username or password incorrect";
			}

		
		// if post upload form is submitted
		} else if (isset($_POST["form"])) {
			if (empty($_POST["desc"])) {
				$descErr = "Description required.";
				$error = true;
			} else {
				$desc = format_input($_POST["desc"]);
			} 	

			if (empty($_POST["agreement"])) {
				$agreeErr = "Please check.";
				$error = true;
			} else {
				$agreement = $_POST["agreement"];
			} // else

			if (empty($_FILES["image"]["name"])) {
				$pfpErr = "Please upload an image for your post.";
				$error = true;
			} else {
				// setting file-related variables if file is uploaded
				$uid = file_get_contents("postid.txt");
				$targetFile = $postDir . basename($_FILES["image"]["name"]);
				$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
				
				// rename target file to uid
				$targetFile = $postDir . $uid . "." . $imageFileType;

				// check if file is an image
				$check = exif_imagetype($_FILES["image"]["tmp_name"]);
				
				if (!($check !== false)) {
					$pfpErr = "File is not an image.";
					$error = true;
					
				// check if file already exists
				} else if (file_exists($targetFile)) {
					$pfpErr = "Image already exists.";
					$error = true;
					
				// check if file is too large
				} else if ($_FILES["image"]["size"] > 4000000) {
					$pfpErr = "Sorry, your file is too large. All files must be under 4MB.";
					$error = true;
					
				// check if file is a valid image type
				} else if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
					$pfpErr = "Sorry, only .jpg, .jpeg, .png, and .gif files are allowed.";
					$error = true;
					
				}
			} // else

		// if logout button is pressed
		} else if (isset($_POST["logout"])) {
			session_unset();
			session_destroy();
		
		// if signup form is submitted
		} else if (isset($_POST["signup"])) {
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
			
			if (empty($_POST["username"])) {
				$userErr = "Username required.";
				$error = true;
			} else { // add error checking for alphanumerical characters only + no whitespace !
				$username = format_input($_POST["username"]);
			} // else

			if (empty($_POST["password"])) {
				$pwdErr = "Password required.";
				$error = true;
			} else {
				$password = format_input($_POST["password"]);
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
			
			$uid = file_get_contents("identifier.txt");

			if (empty($_FILES["image"]["name"])) {
				$imageFileType = "png"; // all default pfps are png files
				$targetFile = $targetDir . $uid . "." . $imageFileType;
				$isPfpUploaded = false;
			} else {
				$isPfpUploaded = true;
				// setting file-related variables if file is uploaded
				$targetFile = $targetDir . basename($_FILES["image"]["name"]);

				$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
				
				// rename target file to uid
				$targetFile = $targetDir . $uid . "." . $imageFileType;

				// check if file is an image
				$check = exif_imagetype($_FILES["image"]["tmp_name"]);
				
				if (!($check !== false)) {
					$pfpErr = "File is not an image.";
					$error = true;
					
				// check if file already exists
				} else if (file_exists($targetFile)) {
					$pfpErr = "Image already exists.";
					$error = true;
					
				// check if file is too large
				} else if ($_FILES["image"]["size"] > 4000000) {
					$pfpErr = "Sorry, your file is too large. All files must be under 4MB.";
					$error = true;
					
				// check if file is a valid image type
				} else if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
					$pfpErr = "Sorry, only .jpg, .jpeg, .png, and .gif files are allowed.";
					$error = true;
					
				}
			} // else
		
		// if follow button is pressed
		} else if (isset($_POST["x"])) {
			follow($_POST["userToFollow"]);
		}
	} // if
	
	// display login or signup if user is not logged in, store data if user signs up
	if (!isset($_SESSION["loggedIn"])) {
		include "loginmenu.inc";
		if (isset($_GET["page"])) {
			if ($_GET["page"] == "signup") {
				include "signupform.inc";
			} else {
				include "loginform.inc";
			}

		// if signup form was submitted successfully
		} else if (isset($_POST["signup"]) && !$error) {
			$_POST["uid"] = $uid;
			$_POST["imagetype"] = $imageFileType;
			$_POST["following"] = array();
			write_data_to_file($file);
			upload_pfp($targetDir, $targetFile, $isPfpUploaded);
			
			//creating user's .json file
			file_put_contents($uid . ".json", "");

			file_put_contents("identifier.txt", ($uid + 1));
			

			if (!is_dir("pfpthumbs/")) {
				mkdir("pfpthumbs/", 0755);
			}
			$dest = "pfpthumbs/" . $uid . "." . $imageFileType;
			
			if (!file_exists($dest)) {
				createThumbnail($targetFile, $dest, 200, 200);	
			}
			
			include "loginform.inc";

		} else if ($error) {
			include "signupform.inc";
		}
		else {
			include "loginform.inc";
		}
		
	} else {
		include "navmenu.inc";
		// if post form was submitted successfully
		if (isset($_POST["form"]) && !$error) {	
			$_POST["uid"] = $uid;
			$_POST["imagetype"] = $imageFileType;

			write_data_to_file($_SESSION["userFile"]);
			upload_pfp($postDir, $targetFile, true);

			file_put_contents("postid.txt", $uid + 1);

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
				$jsonstring = file_get_contents($file);
				
				// decode json string into php array
				$userprofiles = json_decode($jsonstring, true);

				foreach ($userprofiles as $user) {
					$userFile = ($user["uid"] . ".json");
					echo $userFile;
					echo "hi";
					if (file_exists($userFile)) {
						unlink($userFile);
					}
				}

				unlink($file);
			}

			if (is_dir($postDir)) {
				delete_images($postDir);
			}

			if (is_dir($targetDir)) {
				delete_images($targetDir);
			}

			if (is_dir("thumbnails/")) {
				delete_images("thumbnails/");
			}

			if (is_dir("pfpthumbs/")) {
				delete_images("pfpthumbs/");
			}

			file_put_contents("identifier.txt", 1);
			file_put_contents("postid.txt", 1);
			
		} // if

		include "logout.inc";

	}
	
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

	function follow($target) {
		
		$file = "userprofiles.json";

		//decode json string into php array
		if(file_exists($file)){
			$jsonstring=file_get_contents($file);

			$userprofiles = json_decode($jsonstring, true);
		}

		/*if (!isset($userprofiles[$_SESSION["userUid"]-1]["following"])) {
			$userprofiles[$_SESSION["userUid"]-1]["following"][] = $target;
		} else */
		
		if (!in_array($target, $userprofiles[$_SESSION["userUid"]-1]["following"])) {
			$userprofiles[$_SESSION["userUid"]-1]["following"][] = $target;
			
		}

		//add info to array

		//encode back into file
		$jsoncode = json_encode($userprofiles, JSON_PRETTY_PRINT);
		file_put_contents($file, $jsoncode);
		
	} // follow
	
	function upload_pfp($targetDir, $targetFile, $isUploaded) {
		// if targetDir doesn't exist, create it
		if (!is_dir($targetDir)) {
			mkdir($targetDir, 0755);
		}
		
		if ($isUploaded) {
			// upload the image file
			move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
		} else {
			copy("images/" . rand(0, 4) . ".png", $targetFile);
		}
		
		
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