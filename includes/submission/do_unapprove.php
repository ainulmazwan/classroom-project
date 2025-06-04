<?php

    $database = connectToDB();

    $task_id = $_POST["task_id"];
    $class_id = $_POST["class_id"];
    $submission_id = $_POST["submission_id"];

    $status = "pending";

    // update submission status to pending
        $sql = "UPDATE submission set status = :status WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "status" => $status,
            "id" => $submission_id
        ]);

    // redirect to classroom task
    header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
    exit;
?>