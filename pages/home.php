<?php
  $database = connectToDB();

  // get class list
  if (isUserLoggedIn() && isTeacher()){
    // teacher : get classes by teacher
    $sql = "SELECT * FROM class WHERE teacher_id = :teacher_id";
    $query = $database->prepare($sql);
    $query->execute([
      "teacher_id" => $_SESSION["user"]["id"]
    ]);
    $classes = $query->fetchAll();
  }elseif (isStudent()){
    // student : get classes joined by student (using student_in_class)
    $sql = "SELECT class.*, student_in_class.student_id
            FROM class
            JOIN student_in_class
            ON student_in_class.class_id = class.id
            WHERE student_in_class.student_id = :student_id";
    $query = $database->prepare($sql);
    $query -> execute([
      "student_id" => $_SESSION["user"]["id"]
    ]);
    $classes = $query->fetchAll();

  }
?>
<?php require "parts/header.php"; ?>

<div class="container my-5">

  <!-- greeting -->
  <?php if (isUserLoggedIn()): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Welcome back, <span class="text-primary"><?= htmlspecialchars($_SESSION["user"]["name"]); ?></span>!</h4>
      <a href="/auth/logout" class="btn btn-outline-danger btn-sm">Log Out</a>
    </div>

    <?php if (isTeacher()): ?>
      <div class="text-center mb-5">
        <form action="/classroom_create" method="POST" class="d-inline">
          <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>Create a Classroom
          </button>
        </form>
      </div>

      <!-- show classes -->
      <?php if (!empty($classes)): ?>
        <div class="row g-4">
          <?php foreach ($classes as $class) : ?>
            <div class="col-md-4">
              <div class="card h-100 shadow-sm border-0">
                <img src="https://via.placeholder.com/400x200?text=Classroom+Image" class="card-img-top" alt="Classroom Image">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?= $class["subject"] ?></h5>
                  <a href="/classroom?id=<?= $class["id"]; ?>" class="btn btn-primary mt-auto">Manage Class</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-muted">You have not created any classrooms yet.</p>
      <?php endif; ?>

    <?php elseif (isStudent()): ?>
      <div class="text-center mb-5">
        <form action="/classroom_join" method="POST" class="d-inline">
          <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-2"></i>Join a Classroom
          </button>
        </form>
      </div>

      <?php if (!empty($classes)): ?>
        <div class="row g-4">
          <?php foreach ($classes as $class) : ?>
            <div class="col-md-4">
              <div class="card h-100 shadow-sm border-0">
                <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="Classroom Image">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?= $class["subject"] ?></h5>
                  <a href="/classroom?id=<?= $class["id"]; ?>" class="btn btn-outline-primary mt-auto">View Class</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-muted">You have not joined any classrooms yet.</p>
      <?php endif; ?>
    <?php else: ?>
      <div class="container mx-auto my-5" style="max-width: 800px;">
      <h1 class="h1 mb-4 text-center">Dashboard</h1>
      <?php require "parts/message_success.php"; ?>
      <div class="row">
        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-pencil-square" style="font-size: 3rem;"></i>
                </div>
                Manage Classrooms
              </h5>
              <div class="text-center mt-3">
                <a href="/manage_classrooms" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-people" style="font-size: 3rem;"></i>
                </div>
                Manage Users
              </h5>
              <div class="text-center mt-3">
                <a href="/manage_users" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

  <?php else: ?>
    <div class="text-center my-5">
      <h3>Welcome to Goggle Classworm</h3>
      <p class="mb-4">Please log in or sign up to continue.</p>
      <a href="/login" class="btn btn-primary btn-lg me-3">Log In</a>
      <a href="/signup" class="btn btn-outline-primary btn-lg">Sign Up</a>
    </div>
  <?php endif; ?>

</div>

<?php require "parts/footer.php"; ?>
