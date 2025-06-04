<?php
    date_default_timezone_set('Asia/Singapore');
    $database = connectToDB();

    $content = $_POST["content"];
    $submitted_at = date('Y-m-d H:i:s');
    $task_id = $_POST["task_id"];
    $student_id = $_SESSION["user"]["id"];
    $class_id = $_POST["class_id"];

    if ( empty( $content)){
        header("location: /classroom_task?task_id=" . $task_id. "&class_id=" . $class_id);
        exit;
    }

    $sql = "INSERT INTO submission (`content`, `submitted_at`, `task_id`, `student_id`)
            VALUES (:content, :submitted_at, :task_id, :student_id)";
            $query = $database->prepare($sql);
            $query->execute([
                'content' => $content,
                'submitted_at' => $submitted_at,
                'task_id' => $task_id,
                'student_id' => $student_id
            ]);

    // redirect to classroom task
    header("location: /classroom_task?task_id=" . $task_id. "&class_id=" . $class_id);
    exit;
        
?>
            
