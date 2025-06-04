<?php
    $database = connectToDB();

    $subject = $_POST["subject"];
    $teacher_id = $_SESSION["user"]["id"];
    $code = createClassCode();


    if ( empty( $subject)){
        $_SESSION["error"] = "Please enter your class name";
        header("location: /classroom_create");
        exit;
    // make sure passwords match
    }
        
            $sql = "INSERT INTO class (`subject`, `code`, `teacher_id`) VALUES (:subject, :code, :teacher_id)";
            // 6.2 prepare
            $query = $database->prepare( $sql );
            // 6.3 execute
            $query->execute([
                "subject" => $subject,
                "code" => $code,
                "teacher_id" => $teacher_id
            ]);

            // 7. set success message
            $_SESSION["success"] = "Class created successfully";


            // 8. redirect to manage users.php
            header("Location: /");
            exit;

?>