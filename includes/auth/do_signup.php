<?php
    $database = connectToDB();

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];

    if ( empty( $name) || empty( $email) || empty($password) || empty($confirm_password)){
        $_SESSION["error"] = "All fields are required";
        header("location: /signup");
        exit;
    }else if ($password!== $confirm_password) {
        $_SESSION["error"] = "Password does not match";
        header("location: /signup");
        exit;
    }else{
        $user = getUserByEmail($email);
        // check if user exist
        if($user){
            $_SESSION["error"] = "Email already exists";
            header("location: /signup");
            exit;
        }else{
            // 6. create a user account
            // 6.1 SQL command
            $sql = "INSERT INTO user (`name`,`email`,`password`,`role`) VALUES (:name, :email, :password, :role)";
            // 6.2 prepare
            $query = $database->prepare( $sql );
            // 6.3 execute
            $query->execute([
                "name" => $name,
                "email" => $email,
                "password" => password_hash( $password, PASSWORD_DEFAULT ),
                "role" => $role
            ]);

            // 7. set success message
            $_SESSION["success"] = "Account created successfully. Please login with your email and password";


            // 8. redirect to login.php
            header("Location: /login");
            exit;
        }

    } 
?>