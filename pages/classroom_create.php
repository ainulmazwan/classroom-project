<?php
  if (!isTeacher()){
    header("Location: /");
    exit;
  }
?>
<?php require "parts/header.php"; ?>
    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Create a Classroom</h1>

      <!-- form area -->
      <div class="card p-4">
        <form method="POST" action="/classroom/create">
          <div class="mb-2">
            <h4 class="text-center mb-4">What will you name your classroom?</label>
            <input
              type="text"
              class="form-control mt-4"
              id="email"
              placeholder="subject ie: maths, science"
              name="subject"
            />
          </div>
          <div class="d-grid">
            <button type="create" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>

      <!-- go back link -->
      <div
        class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3"
      >
        <a href="/" class="text-decoration-none small"
          ><i class="bi bi-arrow-left-circle"></i> Go back</a
        >
      </div>
    </div>

<?php require "parts/footer.php"; ?>
