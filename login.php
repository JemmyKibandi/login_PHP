if (isset($_POST['login'])) {
    // Get the email and password from the POST data
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Ensure that form fields are filled in properly
    if (empty($email)) {
        $_SESSION['err'] = "* Email is required *";
        header('location: index.php');
        exit();
    }
    if (empty($password)) {
        $_SESSION['err'] = "* Password is required *";
        header('location: index.php');
        exit();
    }
    if (strlen($password) >= 8) {
        $_SESSION['err'] = "* Password is too long *";
        header('location: index.php');
        exit();
    }
    if (strlen($password) < 3) {
        $_SESSION['err'] = "* Password is too short*";
        header('location: index.php');
        exit();
    }
    
    // Check if the user exists in the database
    $query = "SELECT * FROM `user` WHERE `user_email`='$email' ";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) != 1) {
        $_SESSION['err'] = "Enter correct Email/password";
        header('location: index.php');
        exit();
    } else {
        // Check if the password matches the one in the database
        $password = md5($password);
        $query = "SELECT * FROM `user` WHERE `user_email` ='$email' AND `user_password` ='$password'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            // If the password is correct, set session and cookie and redirect to index.php
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_email'] = $row['user_email'];
            setcookie('COOKIE', $_SESSION['user_id'], time() + (86400 * 30), "/");
            header('location: index.php');
            exit();
        } else {
            // If the password is incorrect, show error message and redirect to index.php
            $_SESSION['err'] = " Wrong Password";
            header('location: index.php');
            exit();
        }
    }
}
