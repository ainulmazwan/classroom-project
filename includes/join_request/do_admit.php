<?php
    $database = connectToDB();

    $join_request_id = $_POST["join_request_id"];
    $class_id = $_POST["class_id"];
    $status = "approved";

    $sql = "SELECT * FROM join_request WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "id" => $join_request_id
        ]);
        $join_request = $query->fetch();

        $student_id = $join_request["student_id"];

    $sql = "UPDATE join_request set status = :status WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "status" => $status,
            "id" => $join_request_id
        ]);
    
    $sql = "INSERT INTO student_in_class (`student_id`, `class_id`) VALUES (:student_id, :class_id)";
            // 6.2 prepare
            $query = $database->prepare( $sql );
            // 6.3 execute
            $query->execute([
                "student_id" => $student_id,
                "class_id" => $class_id
            ]);
    
    // Fetch all existing tasks for the class
    $sql = "SELECT * 
            FROM task 
            WHERE class_id = :class_id";
    $query = $database->prepare($sql);
    $query->execute([
        "class_id" => $class_id
    ]);
    $tasks = $query->fetchAll();

    // 2. For each task, insert into student_task
    $sql = "INSERT INTO student_task (student_id, task_id) 
            VALUES (:student_id, :task_id)";
    $query = $database->prepare($sql);
    foreach ($tasks as $task) {
        $query->execute([
            'student_id' => $student_id,
            'task_id' => $task["id"]
        ]);
    }

    header("location: /classroom?id=" . $class_id);
    exit;


?>