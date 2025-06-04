<?php 
    $database = connectToDB();

    $task_id = $_POST["task_id"];
    $class_id = $_POST["class_id"];

    $sql = "DELETE FROM student_task WHERE task_id = :task_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "task_id" => $task_id
    ]);
    
    $sql = "DELETE FROM task WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
        "id" => $task_id
    ]);

    

    header("location: /classroom?id=" . $class_id);
    exit;
?>