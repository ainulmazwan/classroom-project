<?php
    $database = connectToDB();

    $codeAttempt = $_POST["codeAttempt"];
    $student_id = $_SESSION["user"]["id"];

    if ( empty( $codeAttempt)){
        $_SESSION["error"] = "Please enter your class code";
        header("location: /classroom_join");
        exit;
    // make sure passwords match
    }
            $sql = "SELECT * FROM class WHERE code = :codeAttempt";
            $query = $database->prepare($sql);
            $query->execute([
                "codeAttempt" => $codeAttempt
            ]);
            $class = $query->fetch();

            $class_id = $class["id"];

            if ($class) {
                // Code exists, proceed to create join_request
                $class_id = $class["id"];

                // check if join_request for this class already exists
                $sql = "SELECT * FROM join_request WHERE student_id = :student_id AND class_id = :class_id";
                $query = $database->prepare($sql);
                $query->execute([
                    "student_id" => $student_id,
                    "class_id" => $class_id
                ]);

                $existing_join_requests = $query->fetchAll();

                if (count($existing_join_requests) > 0) {
                    // There is at least one existing join request
                    $_SESSION["error"] = "You already requested to join this class";
                    header("location: /classroom_join");
                    exit;
                } else {
                    $sql = "INSERT INTO join_request (student_id, class_id, status, timestamp)
                    VALUES (:student_id, :class_id, 'pending', NOW())";
                    $query = $database->prepare($sql);
                    $query->execute([
                        'student_id' => $student_id,
                        'class_id' => $class_id
                    ]);
                    $_SESSION["success"] = "Join request submitted!";
                    header("location: /classroom_join");
                    exit;
                }
            } else {
                // Code does not exist
                $_SESSION["error"] = "Invalid class code";
                header("location: /classroom_join");
                exit;
            }
            
