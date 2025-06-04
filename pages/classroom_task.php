<?php
    $database = connectToDB();

    $task_id = $_GET["task_id"];
    $class_id = $_GET["class_id"];
    $user_id = $_SESSION["user"]["id"];

    // load task data by id
    $sql = "SELECT * FROM task where id = :id";
    $query = $database->prepare($sql);
    $query -> execute([
      "id" => $task_id
    ]);
    $task = $query->fetch();

    // load class data by id
    $sql = "SELECT * FROM class where id = :class_id";
    $query = $database->prepare($sql);
    $query -> execute([
      "class_id" => $class_id
    ]);
    $class = $query->fetch();



    if (isStudent()) :
        // load submission
        $sql = "SELECT * FROM submission 
                WHERE `task_id` = :task_id 
                AND `student_id` = :student_id";
        $query = $database->prepare($sql);
        $query -> execute([
        "task_id" => $task_id,
        "student_id" => $user_id
        ]);
        $student_submission = $query->fetch();
    else:
        // load ALL task submission
        $sql = "SELECT submission.*, user.name 
                FROM submission
                JOIN user
                ON submission.student_id = user.id
                WHERE submission.task_id = :task_id
                ORDER BY submission.status DESC";
        $query = $database->prepare($sql);
        $query -> execute([
            "task_id" => $task_id
        ]);
        $submissions = $query->fetchAll();

        // load ALL student ids who submit task
        $sql = "SELECT * FROM user WHERE id = :id";
        $query = $database->prepare($sql);
        foreach ($submissions as $submission) {
            $query -> execute([
            "id" => $submission["student_id"]
            ]);
        }
        
        $students = $query->fetchAll();
    endif;

    // load comments by task id
    $sql = "SELECT comment.*, user.name
            FROM comment 
            JOIN user
            ON comment.user_id = user.id
            where task_id = :task_id
            ORDER BY comment.timestamp ASC";
    $query = $database->prepare($sql);
    $query -> execute([
      "task_id" => $task_id
    ]);
    $comments = $query->fetchAll();
?>


<?php require "parts/header.php"; ?>

<div class="container my-5">
    <a href="/classroom?id=<?= $class_id ?>" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Classroom
    </a>
    <div class="row">
        <!-- right side : task info and comments -->
        <div class="col-lg-6 col-md-6 col-sm-12 h-100">
            <!-- task card -->
            <div class="card shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-start card-body">
                    <div>
                        <h3 class="card-title"><?= htmlspecialchars($task["title"]) ?></h3>
                        <p class="card-text"><?= htmlspecialchars($task["description"]) ?></p>
                        <p class="card-text"><small class="text-muted">Posted: <?= htmlspecialchars($task["created_at"]) ?></small></p>
                    </div>
                    <?php if (!isStudent()) : ?>
                    <div class="d-flex">
                        <!-- edit task -->
                        <button type="button" class="btn btn-outline-primary btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#editTaskModal-<?= $task["id"]; ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <div class="modal fade" id="editTaskModal-<?= $task["id"]; ?>" tabindex="-1" aria-labelledby="editTaskLabel<?= $task["id"]; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editTaskLabel<?= $task["id"]; ?>">Edit Task</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/task/edit">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Title</label>
                                                <input required type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>"/>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea required class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($task['description']) ?></textarea>
                                            </div>
                                            <input type="hidden" name="task_id" value="<?= $task["id"]; ?>" />
                                            <input type="hidden" name="class_id" value="<?= $class_id; ?>" />
                                            <button type="submit" class="btn btn-primary">Edit</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- delete task -->
                        <button type="button" class="btn btn-outline-danger btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#deleteTaskModal-<?= $task["id"]; ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                        <div class="modal fade" id="deleteTaskModal-<?= $task["id"]; ?>" tabindex="-1" aria-labelledby="deleteTaskLabel<?= $task["id"]; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteTaskLabel<?= $task["id"]; ?>">Delete Task?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete the task "<strong><?= htmlspecialchars($task["title"]) ?></strong>"?</p>
                                        <p class="text-danger mb-0">This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form method="POST" action="/task/delete" class="d-inline">
                                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>"/>
                                            <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- comments section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-chat-dots text-primary"></i> Comments</h5>
                    <?php if (!empty($comments)): ?>
                        <ul class="list-group mb-3">
                            <?php foreach ($comments as $comment): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>
                                        <strong><?= htmlspecialchars($comment['name']) ?>:</strong>
                                        <div><?= nl2br(htmlspecialchars($comment['content'])) ?></div>
                                        <span class="text-muted small"><?= htmlspecialchars($comment['timestamp']) ?></span>
                                    </div>
                                    <?php if ($comment["user_id"] === $user_id || !isStudent()) : ?>
                                    <div>
                                        <!-- edit comment -->
                                        <button type="button" class="btn btn-outline-primary btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#editCommentModal-<?= $comment["id"]; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <div class="modal fade" id="editCommentModal-<?= $comment["id"]; ?>" tabindex="-1" aria-labelledby="editCommentLabel<?= $comment["id"]; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editCommentLabel<?= $comment["id"]; ?>">Edit Comment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/comment/edit">
                                                            <div class="mb-3">
                                                                <label for="content-<?= $comment["id"]; ?>" class="form-label">Comment</label>
                                                                <textarea required class="form-control" id="content-<?= $comment["id"]; ?>" name="content" rows="3"><?= htmlspecialchars($comment['content']) ?></textarea>
                                                            </div>
                                                            <input type="hidden" name="comment_id" value="<?= $comment["id"]; ?>" />
                                                            <input type="hidden" name="task_id" value="<?= $task["id"]; ?>" />
                                                            <input type="hidden" name="class_id" value="<?= $class_id; ?>" />
                                                            <button type="submit" class="btn btn-primary w-100">Edit</button>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- delete comment -->
                                        <form method="POST" action="/comment/delete" class="d-inline ms-1">
                                            <input type="hidden" name="comment_id" value="<?= $comment["id"] ?>">
                                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                                            <input type="hidden" name="class_id" value="<?= $class_id ?>">
                                            <button class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No comments yet.</p>
                    <?php endif; ?>
                    <!-- add comment -->
                    <form method="POST" action="/comment/add">
                        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                        <input type="hidden" name="class_id" value="<?= $class_id ?>">
                        <div class="mb-2">
                            <textarea class="form-control" name="content" rows="2" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Comment</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- right side : submission section -->
        <div class="col-lg-6 col-md-6 col-sm-12 h-100">
            <div class="card p-3 h-100">
                <?php if (!isStudent()): ?>
                    <h5><i class="bi bi-book text-primary"></i> Student Submissions</h5>
                    <?php if (!empty($submissions)): ?>
                        <ul class="list-group">
                            <?php foreach ($submissions as $submission): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>
                                        <strong><?= htmlspecialchars($submission["name"]) ?>:</strong>
                                        <div class="mt-2"><?= nl2br(htmlspecialchars($submission["content"])) ?></div>
                                        <span class="text-muted"><small><?= htmlspecialchars($submission["submitted_at"]) ?></small></span>
                                    </div>
                                    <div>
                                        <?php if ($submission["status"] === "pending"): ?>
                                            <!-- approve button -->
                                            <form method="POST" action="/submission/approve" class="d-inline">
                                                <input type="hidden" name="submission_id" value="<?= $submission["id"] ?>">
                                                <input type="hidden" name="task_id" value="<?= $task_id ?>">
                                                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                                                <button class="btn btn-outline-secondary btn-sm" title="Approve">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <!-- delete button -->
                                            <form method="POST" action="/submission/delete" class="d-inline ms-1">
                                                <input type="hidden" name="submission_id" value="<?= $submission["id"] ?>">
                                                <input type="hidden" name="task_id" value="<?= $task_id ?>">
                                                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                                                <button class="btn btn-outline-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <!-- unapprove button -->
                                            <form method="POST" action="/submission/unapprove" class="d-inline">
                                                <input type="hidden" name="submission_id" value="<?= $submission["id"] ?>">
                                                <input type="hidden" name="task_id" value="<?= $task_id ?>">
                                                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                                                <button class="btn btn-success btn-sm" title="Unapprove">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No submissions yet.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <h5>Your Submission</h5>
                    <?php if (!empty($student_submission)): ?>
                        <div class="alert alert-success">
                            <strong>Submitted:</strong> <?= $student_submission["submitted_at"] ?><br>
                            <div class="mt-2"><?= $student_submission["content"] ?></div>
                        </div>
                        <form method="POST" action="/task/edit_submission">
                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                            <input type="hidden" name="submission_id" value="<?= $student_submission["id"] ?>">
                            <input type="hidden" name="class_id" value="<?= $class_id ?>">
                            <div class="mb-3">
                                <label class="form-label">Edit your submission:</label>
                                <textarea class="form-control" name="content" rows="3"><?= htmlspecialchars($student_submission["content"]) ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Submission</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="/task/submit">
                            <input type="hidden" name="task_id" value="<?= $task["id"] ?>">
                            <input type="hidden" name="class_id" value="<?= $class_id ?>">
                            <div class="mb-3">
                                <label class="form-label">Submit your answer:</label>
                                <textarea class="form-control" name="content" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php require "parts/footer.php"; ?>
