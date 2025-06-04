<?php 
    $database = connectToDB();

    $title = $_POST["title"];
    $description = $_POST["description"];
    $task_id = $_POST["task_id"];
    $class_id = $_POST["class_id"];

    if (empty($title) || empty($description)){
        $_SESSION["error"] = "Please fill up all the fields";
        header("Location: /classroom_task?task_id=" . $task['id'] . "&class_id=" . $class_id);
        exit;
    }

    // update task
    $sql = "UPDATE task set title = :title, description = :description WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        "title" => $title,
        "description" => $description,
        "id" => $task_id
    ]);

    // redirect to classroom task
    $_SESSION["success"] = "Task has been updated";
    header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
    exit;
?>