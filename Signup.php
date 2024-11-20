<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="styles/Sign.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="form-div">

      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userName = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password1'];
        $confirm_password = $_POST['password2'];

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $errors = [];

        // Validate required fields
        if (empty($userName)) {
            $errors[] = "Username is required.";
        }
        if (empty($email)) {
            $errors[] = "Email is required.";
        }
        if (empty($password)) {
            $errors[] = "Password is required.";
        }
        if (empty($confirm_password)) {
            $errors[] = "Confirm password is required.";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (!empty($password) && strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }

        if (!empty($password) && !empty($confirm_password) && $password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }

        require_once 'config/database.php';

        //check if email registered
        $sql = "SELECT * FROM Users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if($rowCount > 0){
            $errors[] = "Email already registered.";
        }

        //list errors or proceed to add user
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<li class='alert alert-danger'>" . htmlspecialchars($error) . "</li>";
            }
        } else {
            $sql = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                echo "<li class='alert alert-danger'>Error adding User</li>";
                die("SQL Error: " . mysqli_error($conn));
            } else {
                mysqli_stmt_bind_param($stmt, "sss", $userName, $email, $password_hash);
                mysqli_stmt_execute($stmt);
                header("Location: Signin.php");
               
            }
        }
        }
      ?>


      <form name="input" action="" method="POST">
      <h2>Sign Up</h2>
        <p>Username: <input type="text" name="username" size="25"></p>
        <p>Email: <input type="text" name="email" size="25"></p>
        <p>Password: <input type="password" name="password1" size="25"></p>
        <p>Repeat Password: <input type="password" name="password2" size="25"></p>
        <!-- <p>First name: <input type="text" name="fname" size="25"></p>
        <p>Last name: <input type="text" name="lname" size="25"></p> -->
        <p>Already have an account? <a href="Signin.php">Login</a></p>
        <input type="submit" value="Submit"> 
        <input type="reset" value="Reset">
      </form>
    </div>
  </body>
</html>
