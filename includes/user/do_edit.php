<?php

    $database = connectToDB();

    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $new_role = $_POST['new_role'];

    // select old user info
    $sql = "SELECT * FROM user WHERE id = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
        "user_id" => $user_id
    ]);
    $user = $query->fetch();
    $old_role = $user['role'];

// if teacher role change
if ($old_role === 'teacher' && $new_role !== 'teacher') :
    // get all classes by teacher
    $sql = "SELECT * FROM class WHERE teacher_id = :teacher_id";
            $query = $database->prepare($sql);
            $query->execute([
                "teacher_id" => $user_id
            ]);
        $classes = $query->fetchAll();

        // delete from all tables related to class
        foreach ($classes as $class) :
            $class_id = $class["id"];

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
        endforeach;

        // delete user comments
        $sql = "DELETE FROM comment WHERE user_id = :user_id";
        $query = $database->prepare( $sql );
        $query->execute([
            "user_id" => $user_id
        ]);

    endif;

// if student role change
if ($old_role === 'student' && $new_role !== 'student') {
    // delete all join requests
        $sql = "DELETE FROM join_request WHERE student_id = :student_id";
        $query = $database->prepare( $sql );
        $query->execute([
            "student_id" => $user_id
        ]);

        // delete all related student_in_class values
        $sql = "DELETE FROM student_in_class WHERE student_id = :student_id";
        $query = $database->prepare( $sql );
        $query->execute([
            "student_id" => $user_id
        ]);

        // delete all student_tasks with student id
        $sql = "DELETE FROM student_task WHERE student_id = :student_id";
        $query = $database->prepare( $sql );
        $query->execute([
            "student_id" => $user_id
        ]);

        // delete all submissions with student id
        $sql = "DELETE FROM submission WHERE student_id = :student_id";
        $query = $database->prepare( $sql );
        $query->execute([
        "student_id" => $user_id
        ]);

        // delete user comments
        $sql = "DELETE FROM comment WHERE user_id = :user_id";
        $query = $database->prepare( $sql );
        $query->execute([
            "user_id" => $user_id
        ]);
}

if ($old_role === 'admin' && $new_role !== 'admin') {
    // delete user comments
        $sql = "DELETE FROM comment WHERE user_id = :user_id";
        $query = $database->prepare( $sql );
        $query->execute([
            "user_id" => $user_id
        ]);
}
// for all roles (student/teacher/admin)
//  // update everything
    $sql = "UPDATE user set name = :name, email = :email, role = :role WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        "name" => $name,
        "email" => $email,
        "role" => $new_role,
        "id" => $user_id
    ]);

// redirect to manage users
header("Location: /manage_users");
exit;
?>