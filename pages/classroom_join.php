<?php require "parts/header.php"; ?>
    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Join a Classroom</h1>

      <div class="card p-4">
        <form method="POST" action="/join_request/create">
          <div class="mb-2">
            <h4 class="text-center mb-4">Enter the Class Code</h4>
            <!-- display success -->
            <?php require "parts/message_success.php" ?>
            <!--display error -->
            <?php require "parts/message_error.php" ?>
            <input
              type="text"
              class="form-control mt-4"
              placeholder="code ie: aaAA1111"
              name="codeAttempt"
            />
          </div>
          <div class="d-grid">
            <button type="create" class="btn btn-primary">Request to Join</button>
          </div>
        </form>
      </div>

      <!-- links -->
      <div
        class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3"
      >
        <a href="/" class="text-decoration-none small"
          ><i class="bi bi-arrow-left-circle"></i> Go back</a
        >
      </div>
    </div>

<?php require "parts/footer.php"; ?>
