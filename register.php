<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="form-container">
        <p>Register a New User</p>
        <form method="post" action="./registeruser.php"> <!-- Point to Users.php script -->
            <!-- Add form fields  -->
            <div class="form-field">
                <input type="text" name="username" placeholder="User Name">
            </div>
            <div class="form-field">
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="form-field">
                <input type="password" name="confirmPassword" placeholder="Confirm Password">
            </div>
            <div class="form-field">
                <input type="text" name="firstname" placeholder="First Name">
            </div>
            <div class="form-field">
                <input type="text" name="lastname" placeholder="Last Name">
            </div>
            <div class="form-field">
                <input type="text" name="phoneNumber" placeholder="Phone Number">
            </div>
            <div class="form-field">
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
</body>
</html>