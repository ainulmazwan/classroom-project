<?php 
    $database = connectToDB();

    $task_id = $_POST["task_id"];
    $class_id = $_POST["class_id"];

    // delete task comments
    $sql = "DELETE FROM comment WHERE task_id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
        "id" => $task_id
    ]);

    // delete all student_task
    $sql = "DELETE FROM student_task WHERE task_id = :task_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "task_id" => $task_id
    ]);
    
    // FINALLY delete task
    $sql = "DELETE FROM task WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
        "id" => $task_id
    ]);
    
    // redirect to classroom
    header("location: /classroom?id=" . $class_id);
    exit;
?>