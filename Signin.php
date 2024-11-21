<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="styles/Sign.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="form-div">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        require_once 'config/database.php';

        $errors = [];
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (empty($password)) {
            $errors[] = "Password is required.";
        }

        if (empty($errors)) {
            $email = mysqli_real_escape_string($conn, $email);

            $sql = "SELECT * FROM Users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $user = mysqli_fetch_assoc($result);
                if ($user) {
                    if (password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['welcome_message'] = "Welcome, " . $user['username'] . "!";
                        $_SESSION['user_id'] = $user['id'];
                        
                        header('Location: index.php');
                        
                        die();
                    } else {
                        echo "<p style='color:red'>Incorrect password.</p>";
                    }
                } else {
                    echo "<p style='color:red'>Email not found.</p>";
                }
            } else {
                echo "<p style='color:red'>An error occurred. Please try again later.</p>";
            }
        } else {
            foreach ($errors as $error) {
                 echo "<li class='alert alert-danger'>" . htmlspecialchars($error) . "</li>";
            }
        }
    }
    ?>

    <form name="input" action="" method="post">
        <h2>Sign In</h2>
        <p>Email: <input type="email" name="email" size="25"></p>
        <p>Password: <input type="password" name="password" size="25"></p>
        <p>Don't have an account? <a href="Signup.php">Sign up</a></p>
        <p>
            <input type="submit" name="submit" value="Submit">
            <input type="reset" value="Reset">
        </p>
    </form>
</div>
</body>
</html>
