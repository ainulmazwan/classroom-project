<?php 
    $database = connectToDB();

    $student_id = $_POST["student_id"];
    $class_id = $_POST["class_id"];

    // delete join request so student can request again
    $sql = "DELETE FROM join_request WHERE student_id = :student_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "student_id" => $student_id
    ]);
    
    // get class tasks
    $sql = "SELECT * FROM task where class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "class_id" => $class_id
    ]);
    $tasks = $query->fetchAll();

    // delete student_task
    $sql = "DELETE FROM student_task WHERE task_id = :id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "id" => $task["id"]
        ]);
    }

    // delete student comments
    $sql = "DELETE FROM comment WHERE task_id = :task_id AND user_id = :user_id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "task_id" => $task["id"],
        "user_id" => $student_id
        ]);
    }

    // delete student submission
    $sql = "DELETE FROM submission WHERE student_id = :student_id AND task_id = :task_id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "task_id" => $task["id"],
        "student_id" => $student_id
        ]);
    }
    
    // delete student_in_class for the removed student
    $sql = "DELETE FROM student_in_class WHERE student_id = :student_id AND class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "student_id" => $student_id,
        "class_id" => $class_id
    ]);
    
    // redirect to classroom
    header("location: /classroom?id=" . $class_id);
    exit;
?>