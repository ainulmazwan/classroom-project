<?php

    $database = connectToDB();

    $task_id = $_POST["task_id"];
    $class_id = $_POST["class_id"];
    $comment_id = $_POST["comment_id"];

    // delete submission
    $sql = "DELETE FROM comment WHERE id = :comment_id";
    $query = $database->prepare( $sql );
    $query->execute([
        "comment_id" => $comment_id
    ]);

    // redirect
    header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
    exit;
?>