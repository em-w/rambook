<h2>Welcome to RamBook!</h2>
<p class="error">* Required</p>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $username; ?>"> <span class="error"> * <?php echo $userErr; ?></span> <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password"> <span class="error"> * <?php echo $pwdErr; ?></span> <br><br>

    <label for="name">Name:</label><br>
	<input type="text" id="name" name="name" value="<?php echo $name; ?>"> <span class = "error"> * <?php echo $nameErr; ?></span><br><br>	
   
	<label for="pfp">Profile Pic: </label>
	<input type="file" id="pfp" name="pfp" class="button"> <span class="error"> * <?php echo $pfpErr; ?></span> <br><br>
	
    <input type="checkbox" id="agreement" name="agreement" <?php if ($agreement != "") echo "checked"; ?>> 
	
    <label for="agreement">I give RamBook non-exclusive, transferable, sub-licensable, royalty-free, worldwide license to use this photo.</label>
	<span class = "error"> * <?php echo $agreeErr; ?> </span> <br><br>
	
    Connection to Mount Doug: <span class="error"> * <?php echo $connErr; ?></span> <br>
	<div id="fullConnMenu">
		<div id="connectionMenu" class="connMenuElement">
			<input type="radio" id="student" name="connection" value="student" onclick="showGradeMenu()" 
			<?php if ($connection == "student") echo "checked"; ?>>
			<label for="student">Student</label> <br>
			
            <input type="radio" id="alumnus" name="connection" value="alumnus" onclick="hideGradeMenu()" 
			<?php if ($connection == "alumnus") echo "checked"; ?>>
			<label for="alumnus">Alumnus</label> <br>
			
            <input type="radio" id="staff" name="connection" value="staff" onclick="hideGradeMenu()" 
			<?php if ($connection == "staff") echo "checked"; ?>>
			<label for="staff">Staff</label> <br>
		</div>
		<br>
		<div id="gradeMenu" class="connMenuElement">
			<label for="grade">Current Grade:</label>
			<select name="grade" id="grade">
				<option value="9" <?php if($grade == 9) echo "selected"; ?>>9</option>
				<option value="10" <?php if($grade == 10) echo "selected"; ?>>10</option>
				<option value="11" <?php if($grade == 11) echo "selected"; ?>>11</option>
				<option value="12" <?php if($grade == 12) echo "selected"; ?>>12</option>
			</select>
			<br><br>
		</div>
		
	</div> <br><br><br><br>	
	<label for="desc">Say something about yourself:</label> <span class="error"> * <?php echo $descErr; ?></span> <br>
	<textarea id="desc" name="desc" rows="4" cols="50"><?php echo $desc; ?></textarea>
	<br>
	<br>
	<input type=submit value="Submit!" name="signup" class="button">
</form>