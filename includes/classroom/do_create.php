<?php
    $database = connectToDB();

    $subject = $_POST["subject"];
    $teacher_id = $_SESSION["user"]["id"];
    $code = createClassCode();


    if ( empty( $subject)){
        $_SESSION["error"] = "Please enter your class name";
        header("location: /classroom_create");
        exit;
    }
        
            $sql = "INSERT INTO class (`subject`, `code`, `teacher_id`) VALUES (:subject, :code, :teacher_id)";
            $query = $database->prepare( $sql );
            $query->execute([
                "subject" => $subject,
                "code" => $code,
                "teacher_id" => $teacher_id
            ]);

            


            // go to classroom
            header("Location: /");
            exit;

?>