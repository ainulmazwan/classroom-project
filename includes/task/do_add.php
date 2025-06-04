<?php
    date_default_timezone_set('Asia/Singapore');

    $database = connectToDB();

    $title = $_POST["title"];
    $description = $_POST["description"];
    $class_id = $_POST["class_id"];
    $created_at = date('Y-m-d H:i:s');



    if ( empty( $title) || empty( $description)){
        $_SESSION["error"] = "Please fill out all the fields";
        header("location: /classroom?id=" . $class_id);
        exit;
    // make sure passwords match
    }

            // create task        
            $sql = "INSERT INTO task (`title`, `description`, `created_at`, `class_id`) VALUES (:title, :description, :created_at, :class_id)";
            $query = $database->prepare( $sql );
            $query->execute([
                "title" => $title,
                "description" => $description,
                "created_at" => $created_at,
                "class_id" => $class_id
            ]);

            // get the created task id
            $task_id = $database->lastInsertId();

            // get all student id in this class
            $sql = "SELECT student_id FROM student_in_class WHERE class_id = :class_id";
            $query = $database->prepare($sql);
            $query->execute([
                "class_id" => $class_id
            ]);

            $students_in_class = $query->fetchAll();

            foreach ($students_in_class as $i => $student_in_class) {
                var_dump($student_in_class['student_id']);
            }

            // create student_task (medium between student and task)
            $sql = "INSERT INTO student_task (student_id, task_id) VALUES (:student_id, :task_id)";
            $query = $database->prepare($sql);

            foreach ($students_in_class as $i => $student_in_class) {
                $query->execute([
                    'student_id' => $student_in_class['student_id'],
                    'task_id' => $task_id
                ]);
            }


            // redirect to classroom
            header("location: /classroom?id=" . $class_id);
            exit;

?>