<?php
    $database = connectToDB();

    $email = $_POST["email"];
    $password = $_POST["password"];

    // check for error
    if (empty($email) || empty($password)){
        $_SESSION["error"] = "All fields are required";
        //redirect back to login
        header("location: /login");
        exit;
    }else{
        $user = getUserByEmail($email);

        // check if user exists
        if ($user){
            // check if the password is correct or not
            if (password_verify($password, $user["password"])){
                // store the user data in the session storage to login the user
                $_SESSION["user"] = $user;

                header("Location: /");
            }else{
                $_SESSION["error"] = "The password provided is incorrect";
                header("location: /login");
                exit;
            }
        }else{
            $_SESSION["error"] = "Email provided does not exist";
            header("location: /login");
            exit;
        };
    };

?>