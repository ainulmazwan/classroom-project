<?php
    //connect to database
    function connectToDB(){
        $host = "127.0.0.1";
        $database_name = "classroom_project"; // connect to which database
        $database_user = "root";
        $database_password = "";
        
        // 2. connect PHP with MySQL database
        // PDO (PHP database object)
        $database = new PDO(
            "mysql:host=$host;dbname=$database_name",
            $database_user, //username
            $database_password //password
        );
        return $database;
    }


    // get user by email
    function getUserByEmail($email){

        $database = connectToDB();
        // 5.1 SQL command
        $sql = "SELECT * FROM user WHERE email = :email";
        // 5.2 prepare
        $query = $database->prepare($sql);

        // 5.3 execute
        $query -> execute([
            "email" => $email
            
        ]);

        $user = $query->fetch();
        return $user;
    }

    //  check if user is logged in
    function isUserLoggedIn(){
        return isset($_SESSION["user"]);
    }

    // check if user is teacher
    function isTeacher(){
        // check if user session is set or not
        if (isset($_SESSION["user"]) ){
            // check if user is teacher
            if ($_SESSION["user"]["role"]==="teacher"){
                    return true;
            }
        }
    }

    // check if user is student
    function isStudent(){
        // check if user session is set or not
        if (isset($_SESSION["user"]) ){
            // check if user is student
            if ($_SESSION["user"]["role"]==="student"){
                    return true;
            }
        }
    }

    // check if user is admin
    function isAdmin(){
        // check if user session is set or not
        if (isset($_SESSION["user"]) ){
            if ($_SESSION["user"]["role"]==="admin"){
                    return true;
            }
        }
    }

   // create random unique class code
   function createClassCode() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = 62;
        $randomCode = '';
        for ($i = 0; $i < 8; $i++) {
            $randomCode .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomCode;
    }
 

?>