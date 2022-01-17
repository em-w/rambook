<a href="?page=signup">Don't have an account? Sign up instead!:D</a>
<form action="index.php" method="POST">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="passwordField" name="passwordField" onchange="hash()">

    <input type="hidden" id="password" name="password" value="">

    <input type="submit" class="button" name="login" value="login">

    
</form>