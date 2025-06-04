<?php 
    
    $user_id = $_SESSION["user"]["id"];

    $database = connectToDB();

    // load UNFINISHED tasks for THIS student
    /*
        task id = student task id
        student_task.student_id = student_id
        submission = null 
        (submission.student_id = student_id
        AND submission.task_id = task_id)
    */
    $sql = "SELECT task.*, student_task.*
            FROM task
            JOIN student_task
            ON task.id = student_task.task_id
            LEFT JOIN submission
            ON submission.student_id = student_task.student_id
            AND submission.task_id = student_task.task_id
            WHERE student_task.student_id = :student_id
            AND submission.id IS NULL
            ORDER BY task.created_at DESC";
    $query = $database->prepare($sql);
    $query -> execute([
        "student_id" => $user_id
    ]);

    // fetch to do tasks
    $tasks = $query->fetchAll();
?>

<?php require "parts/header.php" ?>
<div class="container my-5">
    <h2 class="text-center mb-4">
        <i class="bi bi-list-check text-primary"></i>
        To-Do List
    </h2>
</div>

<!-- task list -->
    <div class="container" style="max-width:600px;">
            <?php if (count($tasks)): ?>
                <?php foreach ($tasks as $task) : ?>
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1"><?= $task["title"] ?></h5>
                                    <p class="card-text"><?= $task["description"] ?></p>
                                    <p class="card-text"><small class="text-muted">Posted on <?= date('F j, Y, g:i a', strtotime($task["created_at"])) ?></small></p>
                                </div>
                                <div class="d-flex">
                                    <a href="/classroom_task?task_id=<?= $task["task_id"] ?>&class_id=<?= $task["class_id"] ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <!-- no tasks yet -->
            <?php else: ?>
                <div class="alert alert-info text-center mt-4">
                    No work to do...
                </div>
            <?php endif; ?>
    </div>
<?php require "parts/footer.php" ?>