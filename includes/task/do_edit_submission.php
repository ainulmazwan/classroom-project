<?php 
    // if dont set this, time will be wrong
    date_default_timezone_set('Asia/Singapore');
    // connect to database
    $database = connectToDB();

    // get the data from the form
    $submission_id = $_POST["submission_id"];
    $content = $_POST["content"];
    $submitted_at = date('Y-m-d H:i:s');
    $task_id = $_POST["task_id"];
    $student_id = $_SESSION["user"]["id"];
    $class_id = $_POST["class_id"];


    // check error
    if (empty($content)){
        $_SESSION["error"] = "Please fill up all the fields";
        header("Location: /classroom_task?task_id=" . $task['id'] . "&class_id=" . $class_id);
        exit;
    }

        // update submission
        $sql = "UPDATE submission set content = :content, submitted_at = :submitted_at WHERE id = :id";
        $query = $database->prepare($sql);
        $query->execute([
            "content" => $content,
            "submitted_at" => $submitted_at,
            "id" => $submission_id
        ]);

    

    // redirect
    header("Location: /classroom_task?task_id=" . $task_id . "&class_id=" . $class_id);
    exit;
?>