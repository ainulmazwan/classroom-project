<?php
    // if dont set this, timestamp will be wrong
    date_default_timezone_set('Asia/Singapore');

    $database = connectToDB();

    $content = $_POST["content"];
    $timestamp = date('Y-m-d H:i:s');
    $class_id = $_POST["class_id"];
    $task_id = $_POST["task_id"];
    $user_id = $_SESSION["user"]["id"];

    if ( empty( $content)){
        header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
        exit;
    }

    // create comment     
    $sql = "INSERT INTO comment (`content`, `timestamp`, `user_id`, `task_id`) VALUES (:content, :timestamp, :user_id, :task_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        "content" => $content,
        "timestamp" => $timestamp,
        "user_id" => $user_id,
        "task_id" => $task_id
    ]);

    // redirect to classroom task
    header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
    exit;
?>