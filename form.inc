<h2>Make a post!</h2>
<p class="error">* Required</p>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<label for="image">Upload an image:</label>
	<input type="file" id="image" name="image" class="button" onchange="previewImg()"> <span class="error"> * <?php echo $pfpErr; ?></span> <br>
	<img id="preview"><br><br>
	
	<input type="checkbox" id="agreement" name="agreement" <?php if ($agreement != "") echo "checked"; ?>> 
	<label for="agreement">I give RamBook non-exclusive, transferable, sub-licensable, royalty-free, worldwide license to use this photo.</label>
	<span class = "error"> * <?php echo $agreeErr; ?> </span> <br><br>
	
	<label for="desc">Description:</label> <span class="error"> * <?php echo $descErr; ?></span> <br>
	<textarea id="desc" name="desc" rows="4" cols="50"><?php echo $desc; ?></textarea>
	<br>
	<br>
	<input type=submit value="Post!" name="form" class="button">
</form>
