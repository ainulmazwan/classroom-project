<?php
    $database = connectToDB();
    
    $class_id = $_GET["id"];

    // load classroom data by id
    $sql = "SELECT * FROM class where id = :id";
    $query = $database->prepare($sql);
    $query -> execute([
      "id" => $class_id
    ]);
    $class = $query->fetch();

    $teacher_id = $class["teacher_id"];

    // load teacher data by id
    $sql = "SELECT * FROM user where id = :id";
    $query = $database->prepare($sql);
    $query -> execute([
      "id" => $teacher_id
    ]);
    $teacher = $query->fetch();

    // load students in the class with class id
    $sql = "SELECT student_in_class.*, user.name
            FROM student_in_class
            JOIN user
            ON student_in_class.student_id = user.id
            WHERE student_in_class.class_id = :class_id
            AND user.role = 'student'";
    $query = $database->prepare($sql);
    $query -> execute([
      "class_id" => $class_id
    ]);
    $students_in_class = $query->fetchAll();

    // load join requests and requesting students name by id
    $sql = "SELECT join_request.*, user.name
            FROM join_request
            JOIN user
            ON join_request.student_id = user.id
            WHERE join_request.class_id = :class_id
            AND join_request.status = 'pending'
            AND user.role = 'student'";
    $query = $database->prepare($sql);
    $query -> execute([
      "class_id" => $class_id
    ]);
    $join_requests = $query->fetchAll();

    // load class tasks
    $sql = "SELECT *
            FROM task
            WHERE class_id = :class_id
            ORDER BY created_at 
            DESC";
    $query = $database->prepare($sql);
    $query -> execute([
      "class_id" => $class_id
    ]);
    $tasks = $query->fetchAll();

?>
<?php require "parts/header.php"; ?>
<div class="container mt-4">
    <?php if(!isAdmin()) : ?>
    <!-- button to home for !admin -->
    <a href="/" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>
    <?php else: ?>
    <!-- button to class list for admin -->
    <a href="/manage_classrooms" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Classrooms
    </a>
    <?php endif; ?>
</div>

<div class="container mb-5">
    <h1 class="text-center">
        <i class="bi bi-mortarboard text-primary"></i>
        <?= $class["subject"] ?> 
        <span>(<?= $teacher["name"] ?>)</span>
    </h1>
    <?php if (!isStudent()) : ?>
    <p class="text-center mb-1 fs-4">
        code: <?= $class["code"] ?>
    </p>
        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteClassroomModal-<?= $class_id; ?>">
                <i class="bi bi-trash"></i> Delete Classroom
            </button>
            <div class="modal fade" id="deleteClassroomModal-<?= $class_id; ?>" tabindex="-1" aria-labelledby="deleteClassroomLabel<?= $student["student_id"]; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteClassroomModal-<?= $class_id; ?>">Delete Classroom?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to <b>delete</b> this classroom : "<strong><?= $class["subject"] ?></strong>"?</p>
                            <p>Students will be removed and tasks will be deleted.</p>
                            <p class="text-danger mb-0">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="/classroom/do_delete" class="d-inline">
                                <input type="hidden" name="student_id" value="<?= $student["student_id"] ?>"/>
                                <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-dash-lg"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#leaveClassroomModal">
                <i class="bi bi-trash"></i> Leave Classroom
            </button>
            <div class="modal fade" id="leaveClassroomModal" tabindex="-1" aria-labelledby="leaveClassroomLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="leaveClassroomModal">Leave Classroom?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to <b>leave</b> this classroom : "<strong><?= $class["subject"] ?></strong>"?</p>
                            <p>All task submissions will be deleted.</p>
                            <p class="text-danger mb-0">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form method="POST" action="/classroom/do_leave" class="d-inline">
                                <input type="hidden" name="student_id" value="<?= $_SESSION["user"]["id"]; ?>"/>
                                <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-dash-lg"></i> Leave</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- left side : requests and student list -->
        <div class="col-lg-4">
            <?php if (!isStudent()) : ?>
                <!-- see join requests button -->
                <button type="button" class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#seeJoinRequests">
                    <i class="bi bi-person-plus"></i> Join Requests
                </button>
                <div class="modal fade" id="seeJoinRequests" tabindex="-1" aria-labelledby="seeJoinRequestsLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="seeJoinRequestsLabel">Pending Join Requests</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php if (count($join_requests)): ?>
                                    <?php foreach ($join_requests as $i => $join_request) : ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><?= $i+1 . ". " . $join_request["name"]; ?></span>
                                            <div class="d-flex gap-2">
                                                <form method="POST" action="/join_request/admit" class="d-inline">
                                                    <input type="hidden" name="join_request_id" value="<?= $join_request["id"] ?>"/>
                                                    <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                                    <button class="btn btn-success btn-sm" title="Admit">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="/join_request/delete" class="d-inline">
                                                    <input type="hidden" name="join_request_id" value="<?= $join_request["id"] ?>"/>
                                                    <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                                    <button class="btn btn-danger btn-sm" title="Reject">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No pending join requests.</p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-people"></i> Students (<?php echo count($students_in_class) ?>)
                </div>
                    <div class="card p-3">
                    <?php foreach ($students_in_class as $i => $student): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?= ($i+1) . ". " . $student["name"] ?></span>
                            <?php if (!isStudent()): ?>
                                    <!-- remove student button -->
                                    <button type="button" class="btn btn-outline-danger btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#removeStudentModal-<?= $student["student_id"]; ?>">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                    <div class="modal fade" id="removeStudentModal-<?= $student["student_id"]; ?>" tabindex="-1" aria-labelledby="removeStudentLabel<?= $student["student_id"]; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="removeStudentModal-<?= $student["student_id"]; ?>">Remove Student?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to remove the student "<strong><?= $student["name"] ?></strong>"?</p>
                                                    <p class="text-danger mb-0">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="/classroom/remove_student" class="d-inline">
                                                        <input type="hidden" name="student_id" value="<?= $student["student_id"] ?>"/>
                                                        <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                                        <button class="btn btn-danger btn-sm"><i class="bi bi-dash-lg"></i> Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($students_in_class)): ?>
                        <span class="text-muted">No students yet.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- right side : tasks -->
        <div class="col-lg-8">
            <?php if (!isStudent()): ?>
                <!-- add task button -->
                <button type="button" class="btn btn-success mb-4 w-100" data-bs-toggle="modal" data-bs-target="#addTask">
                    <i class="bi bi-plus-circle"></i> Add Task
                </button>
                <?php require "parts/message_error.php"; ?>
                <div class="modal fade" id="addTask" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addTaskLabel">Add New Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="/task/add">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" placeholder="The title of your task" name="title" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" placeholder="The description of your task" name="description" rows="3" required></textarea>
                                    </div>
                                    <input type="hidden" name="class_id" value="<?= $class_id ?>"/>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Create Task</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- task list -->
            <?php if (count($tasks)): ?>
                <?php foreach ($tasks as $task) : ?>
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1"><?= $task["title"] ?></h5>
                                    <p class="card-text"><?= $task["description"] ?></p>
                                    <p class="card-text"><small class="text-muted">Posted on <?= $task["created_at"] ?></small></p>
                                </div>
                                <div class="d-flex">
                                    <a href="/classroom_task?task_id=<?= $task["id"] ?>&class_id=<?= $class_id ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (!isStudent()) : ?>
                                    <!-- delete task button -->
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
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <!-- no tasks yet -->
            <?php else: ?>
                <div class="alert alert-info text-center mt-4">
                    No tasks have been posted yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require "parts/footer.php"; ?>
