<?php
    $database = connectToDB();

    $comment_id = $_POST["comment_id"];
    $content = $_POST["content"];
    $task_id = $_POST["task_id"];
    $class_id = $_POST["class_id"];

    if (empty($content)){
        header("Location: /classroom_task?task_id=" . $task['id'] . "&class_id=" . $class_id);
        exit;
    }
        // update comment
        $sql = "UPDATE comment set content = :content WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "content" => $content,
            "id" => $comment_id
        ]);

    // redirect to classroom task
    header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
    exit;
?>