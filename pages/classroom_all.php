<?php
    if (!isAdmin()){
      header("Location: /");
      exit;
  }
  $search_keyword = isset($_GET["search"]) ? $_GET["search"] : "";


  $database = connectToDB();
 
    // load classroom data by id and search value
    $sql = "SELECT class.*, user.name
            FROM class
            JOIN user
            ON class.teacher_id = user.id
            WHERE (class.subject LIKE :keyword 
            OR user.name LIKE :keyword)";
    $query = $database->prepare($sql);
    $query -> execute([
        "keyword"=>"%$search_keyword%"
    ]);
    $classrooms = $query->fetchAll();
?>
<?php require "parts/header.php"; ?>
<div class="container mt-4">
  <a href="/" class="btn btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left"></i> Back to Home
  </a>
</div>
<div class="container mb-5">
    <h2 class="text-center mb-4">
        <i class="bi bi-collection"></i> All Classrooms
    </h2>

    
      <!-- search -->
      <form method="GET" action="/manage_classrooms" class="mb-2 d-flex align-items-center gap-2">
        <input type="text" name="search" class="form-control" placeholder="Type a keyword to search..." value="<?= $search_keyword?>">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        <a href="/manage_classrooms" class="btn btn-dark">Reset</a>
      </form>

    <?php if (!empty($classrooms)): ?>
        <div class="row g-4">
            <?php foreach ($classrooms as $class): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $class["subject"] ?></h5>
                            <p class="card-text mb-1">
                                <span class="fw-semibold">Teacher:</span> 
                                <?= $class["name"] ?>
                            </p>
                            <p class="card-text mb-1">
                                <span class="fw-semibold">Code:</span> 
                                <?= $class["code"] ?>
                            </p>
                            <a href="/classroom?id=<?= $class["id"]; ?>" class="btn btn-outline-primary mt-auto">
                                <i class="bi bi-eye"></i> View Classroom
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4">
            No classrooms found.
        </div>
    <?php endif; ?>
</div>

<?php require "parts/footer.php"; ?>
