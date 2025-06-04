<?php 
    $database = connectToDB();

    $class_id = $_POST["class_id"];

    // delete all join requests approved or pending
    $sql = "DELETE FROM join_request WHERE class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "class_id" => $class_id
    ]);

    // delete all related student_in_class values
    $sql = "DELETE FROM student_in_class WHERE class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "class_id" => $class_id
    ]);

    // select all task ids where class = deleted class
    $sql = "SELECT * FROM task WHERE class_id = :class_id";
            $query = $database->prepare($sql);
            $query->execute([
                "class_id" => $class_id
            ]);
    $tasks = $query->fetchAll();

    // delete all student_tasks with task ids
    $sql = "DELETE FROM student_task WHERE task_id = :task_id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "task_id" => $task["id"]
        ]);
    }

    // delete all submissions with task ids
    $sql = "DELETE FROM submission WHERE task_id = :task_id";
    $query = $database->prepare( $sql );
    foreach ($tasks as $task) {
        $query->execute([
        "task_id" => $task["id"]
        ]);
    }

    // (FINALLY) delete all class tasks with class id
    $sql = "DELETE FROM task WHERE class_id = :class_id";
    $query = $database->prepare( $sql );
    $query->execute([
    "class_id" => $class_id
    ]);

    // (FINALLY2) delete class with class id
    $sql = "DELETE FROM class WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
    "id" => $class_id
    ]);


    

    header("location: /");
    exit;
?>