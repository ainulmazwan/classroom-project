<?php
    $database = connectToDB();

    $join_request_id = $_POST["join_request_id"];
    $class_id = $_POST["class_id"];
    $status = "approved";

    // get student data with join request id
    $sql = "SELECT * FROM join_request WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        "id" => $join_request_id
    ]);
    $join_request = $query->fetch();

    $student_id = $join_request["student_id"];

    // set join request status to approved
    $sql = "UPDATE join_request set status = :status WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "status" => $status,
            "id" => $join_request_id
        ]);
    
    // add student_in_class value
    $sql = "INSERT INTO student_in_class (`student_id`, `class_id`) VALUES (:student_id, :class_id)";
            $query = $database->prepare( $sql );
            $query->execute([
                "student_id" => $student_id,
                "class_id" => $class_id
            ]);
    
    // fetch all existing tasks for the class
    $sql = "SELECT * 
            FROM task 
            WHERE class_id = :class_id";
    $query = $database->prepare($sql);
    $query->execute([
        "class_id" => $class_id
    ]);
    $tasks = $query->fetchAll();

    // insert all tasks into student_task with student_id
    $sql = "INSERT INTO student_task (student_id, task_id) 
            VALUES (:student_id, :task_id)";
    $query = $database->prepare($sql);
    foreach ($tasks as $task) {
        $query->execute([
            'student_id' => $student_id,
            'task_id' => $task["id"]
        ]);
    }

    // redirect to classroom
    header("location: /classroom?id=" . $class_id);
    exit;


?>