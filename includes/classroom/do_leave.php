<?php 
    $database = connectToDB();

    $student_id = $_POST["student_id"];
    $class_id = $_POST["class_id"];

    // delete join request so student can request again
    $sql = "DELETE FROM join_request WHERE student_id = :student_id AND class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "student_id" => $student_id,
        "class_id" => $class_id
    ]);

    // get class tasks
    // select all task ids where class = deleted class
    $sql = "SELECT * FROM task WHERE class_id = :class_id";
    $query = $database->prepare($sql);
    $query->execute([
        "class_id" => $class_id
    ]);
    $tasks = $query->fetchAll();

    // delete all student_tasks with task ids and student id
    $sql = "DELETE FROM student_task WHERE task_id = :task_id AND student_id = :student_id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "task_id" => $task["id"],
        "student_id" => $student_id
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

    // delete all submissions with task ids and student id
    $sql = "DELETE FROM submission WHERE task_id = :task_id AND student_id = :student_id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "task_id" => $task["id"],
        "student_id" => $student_id
        ]);
    }

    // FINALLY delete student_in_class
    $sql = "DELETE FROM student_in_class WHERE student_id = :student_id AND class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "student_id" => $student_id,
        "class_id" => $class_id
    ]);

    // redirect to home
    header("location: /");
    exit;
?>